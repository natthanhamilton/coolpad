<?php

/**
 * Tests for class-option-text.php
 *
 * @package Titan Framework Tests
 */
class Titan_Framework_Option_Text_Test extends WP_UnitTestCase {
    public $adminPageOption;
    public $adminTabOption;
    public $customizerOption;
    public $metaOption;

    public function setUp() {
        add_action('tf_create_options', [$this, 'tf_create_options']);
        do_action('init');
        parent::setUp();
    }

    public function tearDown() {
        remove_action('tf_create_options', [$this, 'tf_create_options']);
        $titan = TitanFramework::getInstance('testing');
        $titan->deleteAllOptions();
        parent::tearDown();
    }

    public function tf_create_options() {
        $titan = TitanFramework::getInstance('testing');
        $container             = $titan->createAdminPage([
                                                             'name' => 'test container',
                                                         ]);
        $this->adminPageOption = $container->createOption([
                                                              'id'      => 'test1',
                                                              'type'    => 'text',
                                                              'default' => 'default',
                                                          ]);
        $container            = $container->createTab([
                                                          'name' => 'test container',
                                                      ]);
        $this->adminTabOption = $container->createOption([
                                                             'id'      => 'test2',
                                                             'type'    => 'text',
                                                             'default' => 'default',
                                                         ]);
        $container              = $titan->createCustomizer([
                                                               'name' => 'test container',
                                                           ]);
        $this->customizerOption = $container->createOption([
                                                               'id'      => 'test3',
                                                               'type'    => 'text',
                                                               'default' => 'default',
                                                           ]);
        $container        = $titan->createMetaBox([
                                                      'name' => 'test container',
                                                  ]);
        $this->metaOption = $container->createOption([
                                                         'id'      => 'test4',
                                                         'type'    => 'text',
                                                         'default' => 'default',
                                                     ]);
    }

    function test_option_save_get_admin_page() {
        $this->checkValues($this->adminPageOption);
    }

    protected function checkValues($option) {
        $titan = TitanFramework::getInstance('testing');
        $id = $option->settings['id'];
        $this->assertEquals('default', $titan->getOption($id));
        $option->setValue('modified');
        $titan->saveInternalAdminPageOptions(); // Does nothing for non-admin options
        $this->assertEquals('modified', $titan->getOption($id));
        $option->setValue('');
        $titan->saveInternalAdminPageOptions(); // Does nothing for non-admin options
        $this->assertEquals('', $titan->getOption($id));
        $option->setValue('0');
        $titan->saveInternalAdminPageOptions(); // Does nothing for non-admin options
        $this->assertEquals('0', $titan->getOption($id));
    }

    function test_option_save_get_admin_tab() {
        $this->checkValues($this->adminTabOption);
    }

    function test_option_save_get_customizer() {
        $this->checkValues($this->customizerOption);
    }

    function test_option_save_get_meta_box() {
        $p = $this->factory->post->create(['post_title' => 'Test Post']);
        global $post;
        $post = get_post($p);
        setup_postdata($post);
        $this->checkValues($this->metaOption);
    }
}

