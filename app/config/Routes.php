<?php

$this->customRoutes[] = ["install", "index", "home", "index"];

/*
 * Settings routes
 */
$this->customRoutes[] = ["Districts", "*", "settings\Districts", "*"];
$this->customRoutes[] = ["Schools", "*", "settings\Schools", "*"];
$this->customRoutes[] = ["Grades", "*", "settings\Grades", "*"];
$this->customRoutes[] = ["Departments", "*", "settings\Departments", "*"];
$this->customRoutes[] = ["Teams", "*", "settings\Teams", "*"];

