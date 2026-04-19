<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        //Qn1
        $length = 10;
        $width = 20;
        $area = $length*$width;
        $perimeter = 2*($length+$width);
        echo "The area of the Rectangle is $area and the permiter of the Rectangle is $perimeter";
        echo "<br>";

        //Qn2
        $amount = 150.50;
        $vataddedamount = 1.15*$amount;
        echo "Before Vat: $amount ; After Vat: $vataddedamount";
        echo "<br>";

        //Qn3
        $num = 3;
        if($num%2 === 0){
            echo "$num is even";
        }
        else{
            echo "$num is odd";
        }
        echo "<br>";

        //Qn4
        $a = 2;
        $b = 5;
        $c = 4;
        if($a>$b && $a>$c){
            echo "$a is the greatest";
        }
        else if ($b>$a && $b>$c){
            echo "$b is the greatest";
        }
        else if ($c>$a && $c>$b){
            echo "$c is the greatest";
        }
        echo "<br>";

        //Qn5
        for ($i=10; $i<=100; $i++){
            if($i%2 === 0){
                echo "$i ";
            }
        }
        echo "<br>";

        //Qn6
        $array = [1,2,3,4,5,6,7,8,9,10];
        $find = 3;
        for ($i=0; $i<count($array); $i++){
            if($array[$i]===$find){
                echo "$find is at index $i";
            }
        }
        echo "<br>";
        echo "<br>";

        //Qn7
        for ($i=0; $i<3; $i++){
            for($j=0; $j<3; $j++){
                if($j<=$i){
                    echo "* ";
                }
            }
            echo "<br>";
        }
        
        echo "<br>";

        for ($i=1; $i<=3; $i++){
            for ($j=1; $j<=3; $j++){
                if($i<=$j){
                    echo "$j ";
                }
            }
            echo "<br>";

        }

        echo "<br>";

        $alphabets = ['A','B','C','D','E','F'];
        $k = 0;
        for ($i = 1; $i <= 3; $i++) {
            for ($j = 1; $j <= $i; $j++) {
                echo $alphabets[$k] . " ";
                $k++;
            }
            echo "<br>";
        }


    ?>
</body>
</html>