<?php

global $DB;

if(strpos(explode('?', $_SERVER['REQUEST_URI'])[0], 'api/register_visit') !== false) {

    $proyect = $_POST['shortname'] ?? null;
    $ip = $_POST['ip'] ?? null;

    $info = $DB->get_record("SELECT v.id as visit_id, p.id as proyect_id, v.total as total FROM proyects p LEFT JOIN visit v ON v.id_proyect = p.id WHERE shortname = '$proyect'");

    if(isset($info->total) && !empty($info->total)){
        $info->total++;
        $DB->update("UPDATE `visit` SET `total` = $info->total WHERE `id` = $info->visit_id");

        $ip_info = $DB->get_record("SELECT * FROM ip_visit WHERE `id_visit` = $info->visit_id AND ip = '$ip'");

        if(empty($ip_info)){
            $DB->insert("INSERT INTO `ip_visit` (`id_visit`, `ip`, `count`) VALUES ($info->visit_id, '$ip', 1)");
        } else {
            $ip_info->count++;
            $DB->update("UPDATE `ip_visit` SET `count` = $ip_info->count WHERE `id` = $ip_info->id");
        }
    } else {
        $insert_id = $DB->insert("INSERT INTO `visit` (`id_proyect`, `total`) VALUES ($info->proyect_id, 1)");
        var_dump($insert_id);
        $DB->insert("INSERT INTO `ip_visit` (`id_visit`, `ip`, `count`) VALUES ($insert_id, '$ip', 1)");
    }
}