<?php


class Page {
    private $title, $stylesheets=array(), $javascripts=array(), $body;

    function Page() {

    }

    function setTitle($title) {
        $user = new User();
        $location = $title;
        $this->title = $title;
    }

    function addCSS($path) {
        $this->stylesheets[] = $path;
    }

    function addJavascript($path) {
        $this->javascripts[] = $path;
    }

    function startBody() {
        ob_start();
    }

    function endBody() {
        $this->body = ob_get_clean();
    }

    function render($path) {
        ob_start();
        include($path);
        return ob_get_clean();
    }

}