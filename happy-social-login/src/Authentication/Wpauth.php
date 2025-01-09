<?php
namespace HappySocialLogin\Authentication;

use Faker;

class Wpauth{

    private static $instance = null;

    private $profileData; // Response received from Provider

    private $userData; // Final Data for WordPress User Creation

    private $settings;  // Various user related Settings

    public function __construct($profileData) {
        $this->profileData = $profileData;
        $this->settings = get_option('hslogin');
        $this->userData = $this->prepare_userdata();
    }

    public static function getInstance($profileData): ?Wpauth
    {
        if (self::$instance === null) {
            self::$instance = new Wpauth($profileData);
        }
        return self::$instance;
    }

    private function generate_username($firstName, $lastName): string
    {
        if (!empty($firstName) && !empty($lastName)) {
            $username = wp_rand(0, 1) ? $firstName : $lastName;
        } else {
            $username = !empty($firstName) ? $firstName : $lastName;
        }
        $username = strtolower($username);
        $username =  $username . substr( uniqid( '', true ), - 3 );
        return $username; //sanitize_user is not required as it is done by wp_insert_user
    }

    private function generate_random_username(): string
    {
        $faker = Faker\Factory::create();
        $username = $faker->userName();
        $username = $username . substr( uniqid( '', true ), - 3 );
        return $username; //sanitize_user is not required as it is done by wp_insert_user
    }

    private function prepare_userdata(): array
    {
        //user role
        $userRole = $this->settings['user-role'];

        //Name
        $name = ''; $firstName = ''; $lastName = '';
        if ($this->settings['user-fields']['basic']['name']['store'] === 'yes') {
            if (!empty($this->profileData['firstName']) || !empty($this->profileData['lastName'])) {
                $firstName = $this->profileData['firstName'];
                $lastName = $this->profileData['lastName'];
                $name = $firstName . ' ' . $lastName;
            } else {
                $name = !empty($this->profileData['displayName']) ? $this->profileData['displayName'] : '';
                $nameParts = explode(' ', $name, 2);
                $firstName = $nameParts[0];
                $lastName = $nameParts[1] ?? '';
            }
        }

        //Username
        if(!empty($firstName) || !empty($lastName)){
            $username = $this->generate_username($firstName, $lastName);
        }else{
            $username = $this->generate_random_username();
        }

        //Password
        $password = wp_generate_password();

        //Email
        $email = '';
        if( $this->settings['user-fields']['basic']['email']['store'] === 'yes'){
            $email = $this->profileData['email'];
        }

        //user_url
        $website = '';
        if( $this->settings['user-fields']['basic']['website']['store'] === 'yes'){
            $website = $this->profileData['webSiteURL'];
        }

        //Additional Meta Data
        $addFieldSettings = $this->settings['user-fields']['additional'];
        $metaData = [];
        foreach ($addFieldSettings as $field => $settings){
            if($settings['store'] === 'yes'){
                $metaData[$settings['default-meta']] = $this->profileData[$field];
            }
            elseif ($settings['store'] === 'custom-meta'){
                $metaData[$settings['custom-meta']] = $this->profileData[$field];
            }
        }

        // Final Userdata
        $userData = [
            'role'        => $userRole,
            'user_login'  => wp_slash($username),
            'user_pass'   => $password,
            'user_nicename'=> $name,  //TODO: Configurable This is user's slug e.g site.com/author/user_nicename
            'user_email'  => wp_slash($email),
            'user_url'    => $website, // This is user's personal website
            'display_name'=> $name,
            'first_name'  => $firstName,
            'last_name'   => $lastName,
            'meta_input'  => $metaData,
        ];

        return $userData;
    }

    private function maybe_create_user($userData, $retry_count = 0): array
    {
        $max_retries = 10;
        $user_id = wp_insert_user($userData);

        if( !is_wp_error($user_id)){
            return [
                'success' => true,
                'user_id' => $user_id,
                'role' => $userData['role']
            ];
        } else {
            if($user_id->get_error_code() === 'existing_user_login' && $retry_count < $max_retries){
                $retry_count++;
                $userData['user_login'] = $userData['user_login'] . substr(uniqid('', true), -3);
                return $this->maybe_create_user($userData, $retry_count);
            }
            elseif ($user_id->get_error_code() === 'existing_user_email'){
                $user = get_user_by( 'email', $userData['user_email']);
                return [
                    'success' => true,
                    'user_id' => $user->ID,
                    'role' => $user->roles[0]
                ];
            }
            else {
                return [
                    'success' => false,
                    'error_message' => $user_id->get_error_message()
                ];
            }
        }
    }

    public function wp_login(): array
    {
        //Try to create a user first if not exist
        $userData = $this->userData;
        $user = $this->maybe_create_user($userData);


        // Find out Redirection URL for that user as per his/her current Role
        $login_redirection_rules = $this->settings['login-redirection-rules'];
        $user_role = $user['role'];
        $redirect_to = '';

        if(!empty($login_redirection_rules)){
            foreach ($login_redirection_rules as $rule) {
                if ($rule['user-role'] === $user_role) {
                    $redirect_to = $rule['redirect-to'];
                    break;
                }
            }
        }



        // All are perfect. Login to WordPress now
        if($user['success'] === true){
            wp_clear_auth_cookie();
            wp_set_current_user($user['user_id']);
            wp_set_auth_cookie($user['user_id']);
            return [
                'success' => true,
                'message' => __('Logged In Successfully', 'happy-social-login'),
                'redirect_to' => $redirect_to
            ];
        }
        else{
            return [
                'success' => false,
                'error_message' => $user['error_message']
            ];
        }
    }

}