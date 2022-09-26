<?php

class Tree
{
    function parseTree($tree, $root = null)
    {
        $return = array();
        # Traverse the tree and search for direct children of the root 
        foreach ($tree as $child => $parent) {
            # A direct child is found 
            if ($parent == $root) {
                # Remove item from tree (we don't need to traverse this again) 
                unset($tree[$child]);
                # Append the child into result array and parse its children 
                $return[] = array(
                    'name' => $child,
                    'children' => parseTree($tree, $child)
                );
            }
        }
        return empty($return) ? null : $return;
    }

    function printTree($tree)
    {
        if (!is_null($tree) && count($tree) > 0) {
            echo '<ul>';
            foreach ($tree as $node) {
                echo '<li>' . $node['name'];
                printTree($node['children']);
                echo '</li>';
            }
            echo '</ul>';
        }
    }


    // Union de las dos funciones anteriores.
    function parseAndPrintTree($root, $tree)
    {
        $return = array();
        if (!is_null($tree) && count($tree) > 0) {
            echo '<ul>';
            foreach ($tree as $child => $parent) {
                if ($parent == $root) {
                    unset($tree[$child]);
                    echo '<li>' . $child;
                    parseAndPrintTree($child, $tree);
                    echo '</li>';
                }
            }
            echo '</ul>';
        }
    }
}
