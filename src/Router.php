<?php

namespace Reactor\PrimitiveRouter;

class Router {

    function routeStr($path, $tree) {
        $rez = new RouterContext($this->normalizePath($path));
        return $this->route($rez, $tree);
    }

    function route($rez, $tree) {
        $tree_pointer = &$tree;
        $cnt = count($rez->path);
        $this->applyNode($rez, $tree_pointer);
        while ($rez->cursor < $cnt) {
            $step = $rez->path[$rez->cursor];
            if (isset($tree_pointer['nodes'][$step])) {
                $tree_pointer = &$tree_pointer['nodes'][$step];
            } else {
                if (isset($tree_pointer['nodes']['{variable}'])) {
                    $tree_pointer = &$tree_pointer['nodes']['{variable}'];
                } else {
                    return $this->apply404($rez);    
                }
            }
            $this->applyNode($rez, $tree_pointer);
            $rez->cursor++;
        }
        return $rez;
    }

    function applyNode($rez, $node) {
        if (isset($node['name'])) {
            $rez->variables[$node['name']] = $rez->path[$rez->cursor];
        }
        if (isset($node['router'])) {
            call_user_func_array($this->getRouter($node['router']), array($rez, $node));
        }
        if (isset($node['nodes']['404'])) {
            $rez->not_found_node = $node['nodes']['404'];
        }
        if (!isset($node['inherit'])) {
            unset($node['nodes']);
            $rez->node = $node;
            $rez->nodes[] = $node;
        }
    }

    function getRouter($router) {
        return $router;
    }

    function apply404($rez) {
        $rez->node = $rez->not_found_node;
        $rez->not_found = true;
        return $rez;
    }

    function normalizePath($path) {
        $path = trim($path, '/');
        if ($path == '') {
            $path = array();
        } else {
            $path = explode('/', $path);    
        }
        return $path;
    }

}
