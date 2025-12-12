<?php
// mysql_* compatibility layer for mysqli (PHP 8.1)
if (!function_exists('mysql_query')) {
    function mysql_query($query, $conn = null) {
        if ($conn === null) {
            $conn = $GLOBALS['conn'] ?? null;
        }
        return mysqli_query($conn, $query);
    }

    function mysql_fetch_array($result, $result_type = MYSQLI_BOTH) {
        return mysqli_fetch_array($result, $result_type);
    }

    function mysql_fetch_assoc($result) {
        return mysqli_fetch_assoc($result);
    }

    function mysql_num_rows($result) {
        return mysqli_num_rows($result);
    }

    function mysql_insert_id($conn = null) {
        if ($conn === null) {
            $conn = $GLOBALS['conn'] ?? null;
        }
        return mysqli_insert_id($conn);
    }

    function mysql_real_escape_string($unescaped_string, $conn = null) {
        if ($conn === null) {
            $conn = $GLOBALS['conn'] ?? null;
        }
        return mysqli_real_escape_string($conn, $unescaped_string);
    }

    function mysql_error($conn = null) {
        if ($conn === null) {
            $conn = $GLOBALS['conn'] ?? null;
        }
        return mysqli_error($conn);
    }

    function mysql_close($conn = null) {
        if ($conn === null) {
            $conn = $GLOBALS['conn'] ?? null;
        }
        return mysqli_close($conn);
    }
}
?>
