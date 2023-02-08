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

require_once($CFG->dirroot.'/course/lib.php');

// get the named set of sections accessible to this user
function block_sectionmenu_get_course_sections($course, $user) {
    $titles = [];
    $modinfo = get_fast_modinfo($course);
    if ($sections = $modinfo->get_section_info_all()) {
        foreach ($sections as $number => $section) {
            if ($section->uservisible && $section->available) {
                $titles[$number] = get_section_name($course, $section);
            }
        }
    }
    return $titles;
}

// get accessible activities in section 0
function block_sectionmenu_get_zeros($course, $page) {
    $result = [];
    $modinfo = get_fast_modinfo($course);

    if (!empty($modinfo->sections[0])) {
        foreach ($modinfo->sections[0] as $modnumber) {
            $mod = $modinfo->cms[$modnumber];
            if (!$mod->uservisible) { // || !$mod->is_visible_on_course_page()) {
                continue;
            }
            if ($mod->modname === "label" || is_null($mod->url)) { // no url to go to, can't list it
                continue;
            }
            $result[] = [
                "name" => $mod->get_formatted_name(),
                "link" => $mod->url,
                "current" => $mod->url->compare($page->url, URL_MATCH_PARAMS),
            ];
        }
    }

    return $result;
}