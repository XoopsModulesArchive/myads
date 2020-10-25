<?php

// ------------------------------------------------------------------------- //
//               E-Xoops: Content Management for the Masses                  //
//                       < http://www.e-xoops.com >                          //
// ------------------------------------------------------------------------- //
// Original Author: Kazumi Ono
// Author Website : http://www.mywebaddons.com/ , http://www.myweb.ne.jp
// Licence Type   : GPL
// ------------------------------------------------------------------------- //

class PageNav
{
    public $total;

    public $perpage;

    public $current;

    public $url;

    public function __construct($total_items, $items_perpage, $current_start, $start_name = 'start', $extra_arg = '')
    {
        $this->total = (int)$total_items;

        $this->perpage = (int)$items_perpage;

        $this->current = (int)$current_start;

        if ('' != $extra_arg && ('&amp;' != mb_substr($extra_arg, -5) || '&' != mb_substr($extra_arg, -1))) {
            $extra_arg .= '&amp;';
        }

        //$this->url = $GLOBALS['PHP_SELF'].'?'.$extra_arg.trim($start_name).'=';

        $this->url = $_SERVER['PHP_SELF'] . '?' . $extra_arg . trim($start_name) . '=';
    }

    public function renderNav($offset = 4)
    {
        if ($this->total < $this->perpage) {
            return;
        }

        $total_pages = ceil($this->total / $this->perpage);

        if ($total_pages > 1) {
            $ret = '';

            $prev = $this->current - $this->perpage;

            $ret .= '<TABLE WIDTH=100% BORDER=0><TR><TD HEIGHT=1 BGCOLOR="#000000" COLSPAN=3></TD></TR><TR>';

            if ($prev >= 0) {
                $ret .= '<TD ALIGN="LEFT"><a href="' . $this->url . $prev . '">&laquo;&laquo; ' . _CLA_PREV . '</a></TD>';
            } else {
                $ret .= '<TD ALIGN="LEFT"><FONT COLOR="#C0C0C0">&laquo;&laquo; ' . _CLA_PREV . '</FONT></TD>';
            }

            $ret .= '<TD ALIGN="CENTER">';

            $counter = 1;

            $current_page = (int)floor(($this->current + $this->perpage) / $this->perpage);

            while ($counter <= $total_pages) {
                if ($counter == $current_page) {
                    $ret .= '<b>' . $counter . '</b> ';
                } elseif (($counter > $current_page - $offset && $counter < $current_page + $offset) || 1 == $counter || $counter == $total_pages) {
                    if ($counter == $total_pages && $current_page < $total_pages - $offset) {
                        $ret .= '... ';
                    }

                    $ret .= '<a href="' . $this->url . (($counter - 1) * $this->perpage) . '">' . $counter . '</a> ';

                    if (1 == $counter && $current_page > 1 + $offset) {
                        $ret .= '... ';
                    }
                }

                $counter++;
            }

            $ret .= '</TD>';

            $next = $this->current + $this->perpage;

            if ($this->total > $next) {
                $ret .= '<TD ALIGN="RIGHT"><a href="' . $this->url . $next . '">' . _CLA_NEXT . ' &raquo;&raquo;</a></TD>';
            } else {
                $ret .= '<TD ALIGN="RIGHT"><FONT COLOR="#C0C0C0">' . _CLA_NEXT . ' &raquo;&raquo;</FONT></TD>';
            }

            $ret .= '</TR><TR><TD HEIGHT=1 BGCOLOR="#000000" COLSPAN=3></TD></TR></TABLE>';
        }

        return $ret;
    }
}
