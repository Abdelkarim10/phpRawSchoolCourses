<?php

$conn = new mysqli("localhost", "root", "", "webadvanced1");

if ($conn->connect_error) {
    die("Unable to connect: " . $conn->connect_error);
}