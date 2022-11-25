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
 * Class containing data for sectionsmenu block.
 *
 * @package    block_sectionsmenu
 * @copyright  2022 tim st clair
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace block_sectionmenu\output;
defined('MOODLE_INTERNAL') || die();

use renderable;
use renderer_base;
use templatable;

require_once($CFG->dirroot . '/course/lib.php');
require_once($CFG->dirroot . '/blocks/sectionmenu/lib.php');

class main implements renderable, templatable {
    private $sections = [];
    private $current = -1;
    private $zeros = [];

    public function __construct($current = -1, $course, $user, $page) {
        $this->sections = block_sectionmenu_get_course_sections($course,$user);
        $this->zeros = block_sectionmenu_get_zeros($course, $page);
        $this->current = $current;
    }

 

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param \renderer_base $output
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {
    global $COURSE, $PAGE;

        // home link
        $top = [];
        $middle = []; // sections
        foreach ($this->sections as $index => $section) {
            $ar = [
                "name" => $section,
                "link" => (new \moodle_url('/course/view.php', ['id'=>$COURSE->id,'section'=>$index]))->out(false),
                "active" => strcmp($this->current, $index) === 0
            ];
            if ($index === 0) {
                $top[] = $ar;
            } else {
                $middle[] = $ar;
            }
        }

        $bottom = []; // accessible activities from section zero
        foreach ($this->zeros as $mod) {
            $bottom[] = [
                "name" => $mod['name'],
                "link" => $mod['link'],
                "active" => $mod['link']->compare($PAGE->url, URL_MATCH_PARAMS)
            ];
        }

        return [
            "current" => $this->current,
            "top" => $top,
            "middle" => $middle,
            "bottom" => $bottom
        ];
    }
}
