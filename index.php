<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <title>Application of CFG in Syntactic Parsing</title>
</head>

<body class="bg-dark">

    <header>
        <div class="jumbotron jumbotron-fluid bg-dark text-white mb-0">
            <div class="container">
                <div class="row">
                    <div class="col text-center">
                        <h1 class="display-5 px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">Application of Syntactic Parsing of a Balinese Sentence Using CKY Algorithm</h1>
                        <p class="lead">Penerapan Algoritma CYK untuk mengetahui bahasa bali yang valid dan tidak valid</p>
                        <nav class="nav nav-pills flex-column flex-sm-row">
                            <p class="flex-sm-fill text-sm-center"></p>
                            <a class="flex-sm-fill text-sm-center nav-link active" href="index.php">Identifikasi</a>
                            <p class="flex-sm-fill text-sm-center"></p>
                        </nav>
                    </div>
                </div>
            </div>
    </header>
    <div class="jumbotron jumbotron-fluid bg-dark text-white mb-0"></div>

    <div class="container" style="margin-top:-8rem">
        <div class="row">
            <div class="col-md-12 post-outer">
                <div class="card">
                    <div class="card-body p-md-5">
                        <div class="container">
                            <p class="mt-5">
                            <form action="" method="post">
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Masukkan kalimat Bahasa Bali...." name="input" value="<?= isset($_POST['input']) ? $_POST['input'] : '' ?>">
                                    <div class="input-group-append">
                                        <a href="#cky"><button type="submit" name="submit" class="btn btn-primary">Check</button></a>
                                    </div>
                                </div>
                            </form>
                            </p>

                            <?php

                            if (isset($_POST['submit'])) {
                                //connect to database
                                $conn = mysqli_connect("localhost", "root", "", "cfg");

                                $result = mysqli_query($conn, "SELECT * FROM tb_cfg");
                                $result2 = mysqli_query($conn, "SELECT * FROM tb_cfg2");
                                $result3 = mysqli_query($conn, "SELECT * FROM tb_cfg3");
                                $rules = [];
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $rules[] = $row;
                                }
                                while ($row = mysqli_fetch_assoc($result2)) {
                                    $rules[] = $row;
                                }
                                while ($row = mysqli_fetch_assoc($result3)) {
                                    $rules[] = $row;
                                }

                                //string input
                                $input = $_POST['input'];

                                //convert string to lower case
                                $kalimat = strtolower($input);

                                //split string to word and turn in to array
                                $word = explode(" ", $kalimat);
                                //echo("$word");
                                
                                //cyk implementation
                                //table declaration
                                $x = array();

                                for ($i = 0; $i < count($word); $i++) {
                                    for ($j = 0; $j < count($word); $j++) {
                                        $x[$i][$j] = "";
                                    }
                                }

                                //checking using cyk
                                for ($i = 0; $i < count($word); $i++) {
                                    for ($j = $i; $j < count($word); $j++) {
                                        if ($i == 0) {
                                            $has = array();
                                            foreach ($rules as $rule) {
                                                if ($rule['terminal'] == $word[$j]) {
                                                    $has[] = $rule['variabel'];
                                                }
                                            }
                                            $ter = implode(",", $has);
                                            $x[$j - $i][$j] = $ter;
                                        } elseif ($i > 0) {
                                            $cek = array();
                                            for ($k = 0; $k < $i; $k++) {
                                                if ($x[$j - $i][($j - $i) + $k] == "" and $x[($j - $i) + ($k + 1)][$j] != "") {
                                                    $y2 = explode(",", $x[($j - $i) + ($k + 1)][$j]);
                                                    foreach ($y2 as $v) {
                                                        $cek[] = $v;
                                                    }
                                                } elseif ($x[($j - $i) + ($k + 1)][$j] == "" and $x[$j - $i][($j - $i) + $k] != "") {
                                                    $y1 = explode(",", $x[$j - $i][($j - $i) + $k]);
                                                    foreach ($y1 as $v) {
                                                        $cek[] = $v;
                                                    }
                                                } elseif ($x[$j - $i][($j - $i) + $k] == "" and $x[($j - $i) + ($k + 1)][$j] == "") {
                                                    continue;
                                                } elseif ($x[$j - $i][($j - $i) + $k] != "" and $x[($j - $i) + ($k + 1)][$j] != "") {
                                                    $y1 = explode(",", $x[$j - $i][($j - $i) + $k]);
                                                    $y2 = explode(",", $x[($j - $i) + ($k + 1)][$j]);
                                                    for ($m = 0; $m < count($y1); $m++) {
                                                        for ($n = 0; $n < count($y2); $n++) {
                                                            $cek[] = $y1[$m] . " " . $y2[$n];
                                                        }
                                                    }
                                                }
                                            }
                                            $hasil = array();
                                            if ($cek == "") {
                                                $x[$j - $i][$j] = "";
                                            } else {
                                                $cek2 = array();
                                                $cek2 = array_unique($cek);
                                                foreach ($cek2 as $c) {
                                                    foreach ($rules as $rule) {
                                                        if ($c == $rule['terminal']) {
                                                            $hasil[] = $rule['variabel'];
                                                        } else {
                                                            continue;
                                                        }
                                                    }
                                                }
                                            }

                                            if ($hasil == "") {
                                                $x[$j - $i][$j] = "";
                                            } else {
                                                $hasil2 = array();
                                                $hasil2 = array_unique($hasil);
                                                $t = implode(",", $hasil2);

                                                $x[$j - $i][$j] = $t;
                                            }
                                        }
                                    }
                                }


                            ?>

                            <!--Print the cyk table-->
                                <div>
                                    <h4 id="cky" class="display-5 px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">Algoritma CYK</h4>
                                    <table style="font-size:14px;" class="table">
                                        <?php for ($i = 0; $i < count($word); $i++) { ?>
                                            <tr>
                                                <td class="table-success">
                                                    <?php echo $word[$i] ?>
                                                </td>
                                                <?php for ($j = 0; $j < count($word); $j++) { ?>
                                                    <td class="table-primary shadow p-1 mb-5 bg-white">
                                                        <?php echo $x[$i][$j]; ?>
                                                    </td>
                                                <?php } ?>
                                            </tr>
                                        <?php } ?>
                                    </table>
                                </div>


                                <?php

                                //check the string are valid or not
                                $h = 0;
                                $final = explode(",", $x[0][count($word) - 1]);
                                foreach ($final as $accept) {
                                    if ($accept == "Kal") {
                                ?>
                                        <h3 class="display-5 px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center text-success">Valid</h3>
                                    <?php
                                        $h = 1;
                                    }
                                }
                                if ($h == 0) {
                                    ?>
                                    <h3 class="display-5 px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center text-danger">Invalid</h3>
                                <?php
                                }
                                
                            }
                            ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>