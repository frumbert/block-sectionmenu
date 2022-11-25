<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Form for editing HTML block instances.
 *
 * @package   block_sectionmenu
 * @copyright 2022 tim st clair
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_sectionmenu extends block_base {

    function init() {
        $this->title = get_string('pluginname', 'block_sectionmenu');
    }

    function has_config() {
        return true;
    }

    function applicable_formats() {
        return array('all' => true, 'my' => false, 'tag' => false, 'blog' => false);
    }

    function specialization() {
        if (isset($this->config->title)) {
            $this->title = $this->title = format_string($this->config->title, true, ['context' => $this->context]);
        } else {
            $this->title = get_string('newhtmlblock', 'block_sectionmenu');
        }
    }

    function instance_allow_multiple() {
        return true;
    }

    function get_content() {
        global $COURSE, $USER, $PAGE;

        if ($this->content !== NULL) {
            return $this->content;
        }

        // you can't get optional_param's here - something to do with render tree? 🤷

        $current = -1;
        foreach ($PAGE->url->params() as $key => $value) {
            if ($key === "section") $current = $value;
        }

        $renderable = new \block_sectionmenu\output\main($current, $COURSE, $USER, $PAGE);
        $renderer = $this->page->get_renderer('block_sectionmenu');

        $this->content = (object) [
            'text' => $renderer->render($renderable),
            'footer' => ''
        ];

        return $this->content;
    }

     /**
     * The block should only be dockable when the title of the block is not empty
     * and when parent allows docking.
     *
     * @return bool
     */
    public function instance_can_be_docked() {
        return (!empty($this->config->title) && parent::instance_can_be_docked());
    }

    /*
     * Add custom html attributes to aid with theming and styling
     *
     * @return array
     */
    function html_attributes() {
        global $CFG;

        $attributes = parent::html_attributes();

        if (!empty($CFG->block_sectionmenu_allowcssclasses)) {
            if (!empty($this->config->classes)) {
                $attributes['class'] .= ' '.$this->config->classes;
            }
        }

        return $attributes;
    }

    /**
     * Return the plugin config settings for external functions.
     *
     * @return stdClass the configs for both the block instance and plugin
     * @since Moodle 3.8
     */
    public function get_config_for_external() {
        global $CFG;

        // Return all settings for all users since it is safe (no private keys, etc..).
        $instanceconfigs = !empty($this->config) ? $this->config : new stdClass();
        $pluginconfigs = (object) ['allowcssclasses' => $CFG->block_sectionmenu_allowcssclasses];

        return (object) [
            'instance' => $instanceconfigs,
            'plugin' => $pluginconfigs,
        ];
    }
}
