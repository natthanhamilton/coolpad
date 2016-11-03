<?php

/**
 * Tests for titan-framework.php
 *
 * @package Titan Framework Tests
 */
class Titan_Framework_Test extends PHPUnit_Framework_TestCase {
    public $tf_create_options_called = FALSE;
    public $tf_done_called           = FALSE;

    public function tf_create_options() {
        $this->tf_create_options_called = TRUE;
    }

    public function tf_done() {
        $this->tf_done_called = TRUE;
    }

    function test_trigger_actual_option_creation() {
        $o = new TitanFrameworkPlugin();
        $this->tf_create_options_called = FALSE;
        $this->tf_done_called           = FALSE;
        add_action('tf_create_options', [$this, 'tf_create_options']);
        add_action('tf_done', [$this, 'tf_done']);
        $o->trigger_option_creation();
        $this->assertTrue($this->tf_create_options_called);
        $this->assertTrue($this->tf_done_called);
        remove_action('tf_create_options', [$this, 'tf_create_options']);
        remove_action('tf_done', [$this, 'tf_done']);
    }

    function test_plugin_links() {
        $o = new TitanFrameworkPlugin();
        $meta = $o->plugin_links(['existing'], TF_PLUGIN_BASENAME);
        $this->assertContains('existing', $meta, 'Existing meta should not be touched.');
        $this->assertGreaterThan(1, count($meta), 'New meta links should be added');
        $meta = $o->plugin_links(['existing'], 'another-plugin/plugin.php');
        $this->assertContains('existing', $meta, 'Non TF meta should not be touched.');
        $this->assertCount(1, $meta, 'Non TF meta should not be touched.');
    }

    function test_force_load_first() {
        $o = new TitanFrameworkPlugin();
        $plugins = $o->force_load_first(['1', '2']);
        $this->assertEquals(['1', '2'], $plugins);
        $plugins = $o->force_load_first(['1', '2', TF_PLUGIN_BASENAME]);
        $this->assertCount(3, $plugins);
        $this->assertEquals(TF_PLUGIN_BASENAME, $plugins[0]);
        $plugins = $o->force_load_first([TF_PLUGIN_BASENAME, '1', '2']);
        $this->assertCount(3, $plugins);
        $this->assertEquals(TF_PLUGIN_BASENAME, $plugins[0]);
    }
}

