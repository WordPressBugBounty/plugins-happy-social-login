<?php

namespace HappySocialLogin\Integration\Elementor\Widgets\SocialLogin;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use HappySocialLogin\Utils\SocialButtons;
use HappySocialLogin\Providers\ProviderManager;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class SocialLogin extends \Elementor\Widget_Base {

    public function get_name(): string
    {
        return 'hslogin_social_login';
    }

    public function get_title(): string
    {
        return esc_html__( 'Social Login', 'happy-social-login' );
    }

    public function get_icon(): string
    {
        return 'dashicons dashicons-image-filter';
    }

    public function get_categories(): array
    {
        return [ 'basic' ];
    }

    public function get_keywords(): array
    {
        return [ 'happy', 'happy social login', 'happy social', 'login', 'social', 'user', 'account', 'google', 'facebook' ];
    }

    public function get_style_depends(): array
    {
        return [
            'elementor-icons-fa-solid',
            'elementor-icons-fa-brands',
            'hslogin-social-login-buttons-style'
        ];
    }

    public function get_script_depends(): array
    {
        return [
            'hslogin-social-login-buttons-script'
        ];
    }

    public function get_notice_msg(): array
    {
        $settingsUrl = admin_url('admin.php?page=hslogin#tab=providers');
        $enabledProviders = ProviderManager::getInstance()->getEnabledProvidersList();
        $count = count($enabledProviders);
        $list = implode(', ', $enabledProviders);
        if( $count === 0){
            $alert = 'danger';
            $msg = sprintf(
                // Translators: %s is the link to the settings page.
                esc_html__('Social login won\'t work until you enable those providers in %s page.', 'happy-social-login'),
                '<a href="' . $settingsUrl . '" style="border-block-end: 1px dotted blue;">' . esc_html__('settings', 'happy-social-login') . '</a>'
            );
        }elseif($count === 1){
            $alert = 'warning';
            $msg = sprintf(
                // Translators: %s is the link to the settings page.
                esc_html__('Currently enabled provider is %1$s. You can enable more providers in %2$s page.', 'happy-social-login'),
                '<strong>' . $list . '</strong>',
                '<a href="' . $settingsUrl . '" style="border-block-end: 1px dotted blue;">' . esc_html__('settings', 'happy-social-login') . '</a>'
            );
        }else{
            $alert = 'info';
            $msg = sprintf(
            // Translators: The first %1$s is the list of currently enabled providers, and the second %2$s is the link to the settings page.
            esc_html__('Currently enabled providers are %1$s. You can enable more providers in %2$s page.', 'happy-social-login'),
                '<strong>' . $list . '</strong>',
                '<a href="' . $settingsUrl . '" style="border-block-end: 1px dotted blue;">' . esc_html__('settings', 'happy-social-login') . '</a>'
            );
        }

        return [$alert, $msg];
    }

    protected function register_controls(): void
    {
        /**************************************************************************
         * Content Section - Login Buttons
         **************************************************************************/
        $this->start_controls_section(
            'content_section_buttons',
            [
                'label' => esc_html__('Login Buttons', 'happy-social-login'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        //BUTTONS LAYOUT
        $this->add_responsive_control(
            'layout',
            [
                'label' => esc_html__( 'Layout', 'happy-social-login' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'column',
                'options' => [
                    'column' => [
                        'title' => esc_html__( 'Vertical', 'happy-social-login' ),
                        'icon' => 'eicon-editor-list-ul',
                    ],
                    'row' => [
                        'title' => esc_html__( 'Horizontal', 'happy-social-login' ),
                        'icon' => 'eicon-ellipsis-h',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .hslogin-buttons-group' => 'flex-direction: {{VALUE}}',
                ],
            ]
        );

        //BUTTONS GAP
        $this->add_responsive_control(
            'button_space_between',
            [
                'label' => esc_html__('Buttons Gap', 'happy-social-login'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'size' => 20,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .hslogin-buttons-group' => 'gap: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        //LOGIN BUTTONS
        $repeater = new Repeater();
        //Provider - Select
        $repeater->add_control(
            'provider',
            [
                'label' => esc_html__('Provider', 'happy-social-login'),
                'type' => Controls_Manager::SELECT,
                'options' => ProviderManager::getInstance()->getProvidersList(),
                'default' => 'google',
            ]
        );
        //Provider - Icon
        $repeater->add_control(
            'icon',
            [
                'label' => esc_html__('Icon', 'happy-social-login'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'hslogin-google',
                    'library' => 'hslogin',
                ]
            ]
        );
        //Provider - Label
        $repeater->add_control(
            'label',
            [
                'label' => esc_html__('Label', 'happy-social-login'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Continue with Google'
            ]
        );
        //Buttons Repeater
        $this->add_control(
            'buttons',
            [
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'label' => esc_html__('Social Buttons', 'happy-social-login'),
                'show_label' => true,
                'label_block' => true,
                'default' => [
                    [
                        'provider' => 'google',
                        'label' => 'Continue with Google',
                        'icon' => [
                            'value' => 'hslogin-google',
                            'library' => 'hslogin',
                        ],
                    ],
                    [
                        'provider' => 'x',
                        'label' => 'Continue with X(Twitter)',
                        'icon' => [
                            'value' => 'fab fa-x-twitter',
                            'library' => 'fa-brands',
                        ],
                    ],
                    [
                        'provider' => 'facebook',
                        'label' => 'Continue with Facebook',
                        'icon' => [
                            'value' => 'hslogin-facebook',
                            'library' => 'hslogin',
                        ],
                    ]
                ],
                'title_field' => '{{{ elementor.helpers.renderIcon( this, icon, {}, "i", "panel" ) || \'<i class="{{ icon }}" aria-hidden="true"></i>\' }}} {{{hsloginCapitalizeFirstLetter(provider)}}}',
            ]
        );

        //DIVIDER
        $this->add_control(
            'divider',
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        //NOTICE
        [$alert, $msg] = $this->get_notice_msg();
        $this->add_control(
            'enable_providers_notice',
            [
                'type' => Controls_Manager::ALERT,
                'alert_type' => $alert,
                'content' => $msg,
            ]
        );

        $this->end_controls_section();


        /**************************************************************************
         * Style Section - Button
         **************************************************************************/
        $this->start_controls_section(
            'style_section_button',
            [
                'label' => esc_html__('Button', 'happy-social-login'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        //Button - Width
        $this->add_responsive_control(
            'button_width',
            [
                'label' => esc_html__('Width', 'happy-social-login'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 270,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .hslogin-button' => 'width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        //Button - Height
        $this->add_responsive_control(
            'button_height',
            [
                'label' => esc_html__('Height', 'happy-social-login'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 45,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .hslogin-button' => 'height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        //Button - margin
        $this->add_responsive_control(
            'button_margin',
            [
                'label' => esc_html__( 'Margin', 'happy-social-login' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .hslogin-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        //Button - Padding
        $this->add_responsive_control(
            'button_padding',
            [
                'label' => esc_html__( 'Padding', 'happy-social-login' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .hslogin-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        //Button - Border Radius
        $this->add_responsive_control(
            'button_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'happy-social-login' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .hslogin-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        //Button - Border Style
        $this->add_control(
            'button_border_style',
            [
                'label' => esc_html__( 'Border Style', 'happy-social-login' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => esc_html__( 'None', 'happy-social-login' ),
                    'solid' => esc_html__( 'Solid', 'happy-social-login' ),
                    'double' => esc_html__( 'Double', 'happy-social-login' ),
                    'dotted' => esc_html__( 'Dotted', 'happy-social-login' ),
                    'dashed' => esc_html__( 'Dashed', 'happy-social-login' ),
                    'groove' => esc_html__( 'Groove', 'happy-social-login' ),
                    'ridge' => esc_html__( 'Ridge', 'happy-social-login' ),
                    'inset' => esc_html__( 'Inset', 'happy-social-login' ),
                    'outset' => esc_html__( 'Outset', 'happy-social-login' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .hslogin-button' => 'border-style: {{VALUE}};',
                ],
            ]
        );
        //Button - Border Width
        $this->add_responsive_control(
            'button_border_width',
            [
                'label' => esc_html__( 'Border Width', 'happy-social-login' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .hslogin-button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'button_border_style',
                            'operator' => '!=',
                            'value' => 'none',
                        ],
                    ],
                ],
            ]
        );
        //Button - Background
        $this->start_controls_tabs('tabs_button_style');
        //Button - Style:NORMAL
        $this->start_controls_tab(
            'tab_button_normal',
            [
                'label' => esc_html__( 'Normal', 'happy-social-login' ),
            ]
        );
        //Button - Border color
        $this->add_control(
            'button_border_color',
            [
                'label' => esc_html__('Border Color', 'happy-social-login'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hslogin-button' => 'border-color: {{VALUE}}',
                ],
            ]
        );
        //Button - Background Type
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'background_type',
                'types' => [ 'classic', 'gradient' ],
                'exclude' => [ 'image' ],
                'selector' => '{{WRAPPER}} .hslogin-button',
            ]
        );
        $this->end_controls_tab();
        //Button - Style:HOVER
        $this->start_controls_tab(
            'tab_button_hover',
            [
                'label' => esc_html__( 'Hover', 'happy-social-login' ),
            ]
        );
        //Button - Border Color:HOVER
        $this->add_control(
            'button_border_color_hover',
            [
                'label' => esc_html__('Border Color', 'happy-social-login'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hslogin-button:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );
        //Button - Background Color:HOVER
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'background_hover',
                'types' => [ 'classic', 'gradient' ],
                'exclude' => [ 'image' ],
                'selector' => '{{WRAPPER}} .hslogin-button:hover',
            ]
        );
        //Button - Hover Transition Duration
        $this->add_control(
            'button_hover_transition_duration',
            [
                'label' => esc_html__('Hover Duration', 'happy-social-login'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['s', 'ms', 'custom'],
                'default' => [
                    'unit' => 's',
                    'size' => 0.3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .hslogin-button' => 'transition: background-color {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();


        /**************************************************************************
         * Style Section - Icon
         **************************************************************************/
        $this->start_controls_section(
            'style_section_icon',
            [
                'label' => esc_html__('Icon', 'happy-social-login'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        //Icon - Alignment
        $this->add_responsive_control(
            'icon_align',
            [
                'label' => esc_html__('Alignment', 'happy-social-login'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'start' => [
                        'title' => esc_html__('Left', 'happy-social-login'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'happy-social-login'),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'end' => [
                        'title' => esc_html__('Right', 'happy-social-login'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .hslogin-icon-wrapper' => 'justify-content: {{VALUE}}',
                ]
            ]
        );
        //Icon - Size
        $this->add_responsive_control(
            'icon_size',
            [
                'label' => esc_html__('Icon Size', 'happy-social-login'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'selectors' => [
                    '{{WRAPPER}} .hslogin-icon-wrapper > *' => 'font-size: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        //Icon - Container Width
        $this->add_responsive_control(
            'icon_container_width',
            [
                'label' => esc_html__('Container Width', 'happy-social-login'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%'],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .hslogin-icon-wrapper' => 'width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        //Icon - Container Height
        $this->add_responsive_control(
            'icon_container_height',
            [
                'label' => esc_html__('Container Height', 'happy-social-login'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'selectors' => [
                    '{{WRAPPER}} .hslogin-icon-wrapper' => 'height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        //Icon - Container Radius
        $this->add_responsive_control(
            'icon_container_radius',
            [
                'label' => esc_html__('Container Radius', 'happy-social-login'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .hslogin-icon-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );
        //Icon - Style:NORMAL
        $this->start_controls_tabs('tabs_icon_style');
        $this->start_controls_tab(
            'tab_icon_normal',
            [
                'label' => esc_html__( 'Normal', 'happy-social-login' ),
            ]
        );
        //Icon - Color:NORMAL
        $this->add_control(
            'icon_color',
            [
                'label' => esc_html__('Icon Color', 'happy-social-login'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hslogin-button .hslogin-icon' => 'color: {{VALUE}}; fill: {{VALUE}}',
                ],
            ]
        );
        //Icon - Container:NORMAL
        $this->add_control(
            'icon_container_color',
            [
                'label' => esc_html__('Container Color', 'happy-social-login'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hslogin-button .hslogin-icon-wrapper' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        $this->end_controls_tab();
        //Icon - Style:HOVER
        $this->start_controls_tab(
            'tab_icon_hover',
            [
                'label' => esc_html__( 'Hover', 'happy-social-login' ),
            ]
        );
        //Icon - Color:HOVER
        $this->add_control(
            'icon_color_hover',
            [
                'label' => esc_html__('Icon Color', 'happy-social-login'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hslogin-button:hover .hslogin-icon' => 'color: {{VALUE}}; fill: {{VALUE}}',
                ],
            ]
        );
        //Icon - Container:HOVER
        $this->add_control(
            'icon_container_color_hover',
            [
                'label' => esc_html__('Container Color', 'happy-social-login'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hslogin-button:hover .hslogin-icon-wrapper' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        //Icon - Hover Transition Duration
        $this->add_control(
            'icon_hover_transition_duration',
            [
                'label' => esc_html__('Hover Duration', 'happy-social-login'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['s', 'ms', 'custom'],
                'default' => [
                    'unit' => 's',
                    'size' => 0.3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .hslogin-button .hslogin-icon' => 'transition: color {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .hslogin-button .hslogin-icon-wrapper' => 'transition: background-color {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();


        /**************************************************************************
         * Style Section - Label
         **************************************************************************/
        $this->start_controls_section(
            'style_section_label',
            [
                'label' => esc_html__('Label', 'happy-social-login'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        //Label - Typography
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'label_typography',
                'label' => esc_html__('Typography', 'happy-social-login'),
                'selector' => '{{WRAPPER}} .hslogin-button .hslogin-label',
            ]
        );
        //Label - Alignment
        $this->add_responsive_control(
            'label_align',
            [
                'label' => esc_html__('Alignment', 'happy-social-login'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'start' => [
                        'title' => esc_html__('Left', 'happy-social-login'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'happy-social-login'),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'end' => [
                        'title' => esc_html__('Right', 'happy-social-login'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .hslogin-label-wrapper' => 'justify-content: {{VALUE}}',
                ]
            ]
        );
        //Label - Icon Spacing
        $this->add_responsive_control(
            'label_spacing',
            [
                'label' => esc_html__('Icon Spacing', 'happy-social-login'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .hslogin-label' => 'margin-left: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        //Label - Container Spacing
        $this->add_responsive_control(
            'label_container_spacing',
            [
                'label' => esc_html__('Container Spacing', 'happy-social-login'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'size_units' => ['px', 'em', 'rem'],
                'selectors' => [
                    '{{WRAPPER}} .hslogin-label-wrapper' => 'margin-left: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        //Label - Container Border Radius
        $this->add_responsive_control(
            'label_container_radius',
            [
                'label' => esc_html__('Container Border Radius', 'happy-social-login'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .hslogin-label-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );
        //Label - Container Border Style
        $this->add_control(
            'label_container_border_style',
            [
                'label' => esc_html__('Container Border Style', 'happy-social-login'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => esc_html__('None', 'happy-social-login'),
                    'solid' => esc_html__('Solid', 'happy-social-login'),
                    'double' => esc_html__('Double', 'happy-social-login'),
                    'dotted' => esc_html__('Dotted', 'happy-social-login'),
                    'dashed' => esc_html__('Dashed', 'happy-social-login'),
                    'groove' => esc_html__('Groove', 'happy-social-login'),
                    'ridge' => esc_html__('Ridge', 'happy-social-login'),
                    'inset' => esc_html__('Inset', 'happy-social-login'),
                    'outset' => esc_html__('Outset', 'happy-social-login'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .hslogin-label-wrapper' => 'border-style: {{VALUE}}',
                ],
            ]
        );
        //Label - Container Border Width
        $this->add_responsive_control(
            'label_container_border_width',
            [
                'label' => esc_html__('Container Border Width', 'happy-social-login'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .hslogin-label-wrapper' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
                'condition' => [
                    'label_container_border_style!' => 'none',
                ],
            ]
        );
        //Label - Style:NORMAL
        $this->start_controls_tabs('tabs_label_style');
        $this->start_controls_tab(
            'tab_label_normal',
            [
                'label' => esc_html__( 'Normal', 'happy-social-login' ),
            ]
        );
        //Label - Color:NORMAL
        $this->add_control(
            'label_text_color',
            [
                'label' => esc_html__('Color', 'happy-social-login'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hslogin-button .hslogin-label' => 'color: {{VALUE}}',
                ],
            ]
        );
        //Label - Container:NORMAL
        $this->add_control(
            'label_background_color',
            [
                'label' => esc_html__('Container Color', 'happy-social-login'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hslogin-button .hslogin-label-wrapper' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        $this->end_controls_tab();
        //Label - Style:HOVER
        $this->start_controls_tab(
            'tab_label_hover',
            [
                'label' => esc_html__( 'Hover', 'happy-social-login' ),
            ]
        );
        //Label - Color:HOVER
        $this->add_control(
            'label_text_color_hover',
            [
                'label' => esc_html__('Hover Color', 'happy-social-login'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hslogin-button:hover .hslogin-label' => 'color: {{VALUE}}',
                ],
            ]
        );
        //Label - Container:HOVER
        $this->add_control(
            'label_background_color_hover',
            [
                'label' => esc_html__('Container Color', 'happy-social-login'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hslogin-button:hover .hslogin-label-wrapper' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        //Label - Hover Transition Duration
        $this->add_control(
            'label_hover_transition_duration',
            [
                'label' => esc_html__('Hover Duration', 'happy-social-login'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['s', 'ms', 'custom'],
                'default' => [
                    'unit' => 's',
                    'size' => 0.3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .hslogin-button .hslogin-label' => 'transition: color {{SIZE}}{{UNIT}}, background-color {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    protected function render(): void
    {
        $settings = $this->get_settings_for_display();
        $buttons = $settings['buttons'];
        SocialButtons::render_social_login_buttons_html($buttons);
    }

}
