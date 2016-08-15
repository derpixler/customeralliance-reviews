<?php
/**
 * Helper functions.
 *
 * Needed for rounding, formatting, xml retrieving and pagination rendering.
 *
 * @package WordPress
 * @subpackage Customer Alliance Reviews
 * @since Customer Alliance Reviews 0.1.0
 */

//returns a float with only one digit after the dot or rounds up
function reasonableRound($rating)
{
    $roundUp = number_format(floatval($rating), 1);
    return $roundUp + 0;
}

//returns a german date from an xml datestring
function dateFormat($date)
{
    $timestamp = strtotime($date);
    return date("d.m.Y", $timestamp);
}

//fetches data from an url
function getXML($url)
{
    $curl = curl_init();

    curl_setopt_array($curl, Array(
        CURLOPT_URL => $url,
        CURLOPT_USERAGENT => 'spider',
        CURLOPT_TIMEOUT => 120,
        CURLOPT_CONNECTTIMEOUT => 30,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_ENCODING => 'UTF-8'
    ));

    $data = curl_exec($curl);
    curl_close($curl);

    return new SimpleXMLElement($data);
}

function renderPagination($reviewCount, $idx)
{
    $outputHtml = '';

    // number of rows to show per page
    $rowsperpage = 20;
    // calculate total pages
    $totalpages = ceil($reviewCount / $rowsperpage);

    // get the current page or set a default
    if (isset($idx) && is_numeric($idx)) {
        // cast var as int
        $currentpage = $idx;
    } else {
        // default page num
        $currentpage = 1;
    } // end if

    // if current page is greater than total pages...
    if ($currentpage > $totalpages) {
        // set current page to last page
        $currentpage = $totalpages;
    } // end if
    // if current page is less than first page...
    if ($currentpage < 1) {
        // set current page to first page
        $currentpage = 1;
    } // end if

    /******  build the pagination links ******/
    // range of num links to show
    $range = 2;

    // if not on page 1, don't show back links
    if ($currentpage > 1) {
        // show << link to go back to page 1
        $outputHtml .= '<a class="page backward" href="' . add_query_arg('review-page', 1) . '"><<</a>';
        // get previous page num
        $prevpage = $currentpage - 1;
        // show < link to go back to 1 page
        $outputHtml .= '<a class="page backward" href="' . add_query_arg('review-page', $prevpage) . '"><</a>';
    } // end if

    // loop to show links to range of pages around current page
    for ($x = ($currentpage - $range); $x < (($currentpage + $range) + 1); $x++) {
        // if it's a valid page number...
        if (($x > 0) && ($x <= $totalpages)) {
            // if we're on current page...
            if ($x == $currentpage) {
                // 'highlight' it but don't make a link
                $outputHtml .= '<b class="page current">' . $x . '</b>';
                // if not current page...
            } else {
                // make it a link
                $outputHtml .= '<a class="page" href="' . add_query_arg('review-page', $x) . '">' .$x . '</a>';
            } // end else
        } // end if
    } // end for

    // if not on last page, show forward and last page links
    if ($currentpage != $totalpages) {
        // get next page
        $nextpage = $currentpage + 1;
        // echo forward link for next page
        $outputHtml .= '<a class="page forward" href="' . add_query_arg('review-page', $nextpage) . '">></a>';
        // echo forward link for lastpage
        $outputHtml .= '<a class="page forward" href="' . add_query_arg('review-page', $totalpages) . '">>></a>';
    } // end if
    /****** end build pagination links ******/

    return $outputHtml;
}