<?php
include('helpers.php');
include('path.php');


$paths = [
    "/home/user/folder1/f1.1/text1.txt",
    "/home/user/folder1/f1.1/text2.txt",
    "/home/user/folder1/f1.1/folder3/text3.txt",
    "/home/user/folder1/text4.txt",
    "/home/user/folder1/f1.1/folder3/text5.txt",
    "/home/user/folder1/f1.1/text6.txt",
    "/home/user/folder1/f1.2/text7.txt",
    "/home/user/folder1/f1.3/text8.txt",
    "/program/user/folder1/folder2/text1.txt",
    "/program/user2/folder1/folder2/text2.txt",
    "/program/user/folder1/folder2/folder3/text3.txt",
    "/Data/user/folder1/text4.txt",
    "/Data/text4.txt",
];

/**
 * Uncomment then run
 */
echo "<h3>Paths to Tree Array</h3>";
$tree1 = new Path();
display_array($tree1::convertToTree($paths));


/**
 * Uncomment then run
 */
echo "<h3>Limit Tree Array</h3>";
$branch = 2;
$leaf = 2;
echo "<h4>Branch = $branch |  Leaf = $leaf</h4>";
display_array(Path::printTree($paths, $branch, $leaf));


/**
 * Uncomment then run
 */
echo "<h3>Generate Paths</h3>";
$new_paths = new Path();
$paths = 7;
$depth = 4;
$files = 4;
echo "<h4>Paths: $paths   |   Depth: $depth   |   Files: $files</h4>";
display_array($new_paths::create('/home/user/',$paths,$depth,$files));

?>