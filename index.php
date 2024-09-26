<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ice Cream Billing System</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            width: 60%;
            margin: auto;
        }
        table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .total {
            text-align: right;
            font-weight: bold;
        }
        button {
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        select {
            padding: 8px;  /* Adds padding inside the select box */
            font-size: 14px; /* Adjusts the font size */
        }
        input {
            padding: 8px;  /* Adds padding inside the select box */
            font-size: 14px; /* Adjusts the font size */
        }
    </style>
    <script type="text/javascript">
        $(document).ready(function() {
          $('.unit').change(function() {
            var selectedValue = $(this).val();  // Get the value of the selected option
            console.log("Selected value: " + selectedValue);
            
            var dropdownName = $(this).attr('name');  // Get the 'name' attribute of the dropdown
            var dropdownName = dropdownName.substring(0, dropdownName.length - 5);
            console.log("Dropdown name: " + dropdownName);

            var selectedIndex = $(this).prop('selectedIndex');  // Get the index of the selected option
            console.log("Selected option index: " + selectedIndex);
            selectedIndex = parseInt(selectedIndex);

            if(selectedIndex == 1)
                $('#'+dropdownName+'_unitNm').val('Cup');
            else if(selectedIndex == 2)                
                $('#'+dropdownName+'_unitNm').val('250 Gm');
            else if(selectedIndex == 3) 
                $('#'+dropdownName+'_unitNm').val('500 Gm');
            else if(selectedIndex == 4) 
                $('#'+dropdownName+'_unitNm').val('1 Kg');

            var selectedSellUnit = $('#'+dropdownName+'_unitNm').val();
            console.log("Selected Sell Unit: " + selectedSellUnit);    

            // You can also get the text of the selected option
            var selectedText = $('#unit option:selected').text();
            console.log("Selected text: " + selectedText);
          });
        });
    </script>
    <script>
        function printBill() {
            var divContents = document.getElementById("bill").innerHTML;
            var a = window.open('', '', 'height=500, width=500');
            a.document.write('<html>');
            a.document.write('<body>');
            a.document.write(divContents);
            a.document.write('</body></html>');
            a.document.close();
            a.print();
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Ice Cream Billing System !!</h1>
        <form method="post" action="">
            <h3>Select Flavours and Quantity:</h3>
            <table>
                <tr>
                    <th>Flavour</th>
                    <th>Unit (Cup/250 gm/500 gm/1 kg)</th>
                    <th>Quantity</th>
                </tr>
                <?php
                // Ice cream flavor price list
                $flavors = [
                    'Vanila_Beans' => [40, 80, 160, 320],
                    'Mava_Malai' => [40, 100, 200, 400],
                    'Rose_Petals' => [40, 100, 200, 400],
                    'Custard_Apple_S' => [50, 120, 230, 450],
                    'Spicy_Guava_S' => [50, 120, 230, 450],
                    'Mango_S' => [50, 120, 240, 480],
                    'Kesar_Pista' => [50, 130, 250, 500],
                    'Choco_Bliss' => [50, 130, 250, 500],
                    'Kaju_Katri' => [60, 140, 280, 550],
                    'Anjeer_Badam' => [60, 140, 280, 550],
                    'Tender_Coconut' => [60, 140, 280, 550],
                    'Black_Jamun_S' => [60, 140, 280, 550],
                    'Strawberry_S' => [70, 170, 330, 650],
                    'Lychee_S' => [70, 180, 350, 700],
                    'Special_Vintage_Flavor' => [70, 170, 330, 650],
                    'Blueberry_S' => [90, 250, 450, 900]
                ];

                // Output selection rows for each flavor
                foreach ($flavors as $flavor => $prices) {
                    echo "<tr>
                        <td>$flavor</td>
                        <td>
                            <select name='{$flavor}_unit' class='unit' id='unit'>
                                <option value='0'>Select Unit</option>
                                <option value='{$prices[0]}'>1 Cup - ₹{$prices[0]}</option>
                                <option value='{$prices[1]}'>250 gm - ₹{$prices[1]}</option>
                                <option value='{$prices[2]}'>500 gm - ₹{$prices[2]}</option>
                                <option value='{$prices[3]}'>1 kg - ₹{$prices[3]}</option>
                            </select>
                            <input type='hidden' name='{$flavor}_unitNm' id='{$flavor}_unitNm' />
                        </td>
                        <td>
                            <input type='number' name='{$flavor}_quantity' value='1' min='1' />
                        </td>
                    </tr>";
                }
                ?>
            </table>
            <button type="submit" name="generate_bill">Generate Bill</button>
        </form>

        <?php
        if (isset($_POST['generate_bill'])) {
            $total = 0;
            $bill_details = [];

            // Calculate total and generate bill details
            foreach ($flavors as $flavor => $prices) {
                // Fetching unit price and quantity for the selected flavor
                $unit_price = isset($_POST["{$flavor}_unit"]) ? (float)$_POST["{$flavor}_unit"] : 0;
                $quantity = isset($_POST["{$flavor}_quantity"]) ? (int)$_POST["{$flavor}_quantity"] : 0;
                $sell_unit = $_POST["{$flavor}_unitNm"];
                // Check if valid unit and quantity are selected
                if ($unit_price > 0 && $quantity > 0) {
                    $cost = $unit_price * $quantity;
                    $total += $cost;
                    $bill_details[] = [
                        'flavor' => $flavor,
                        'unit_price' => $unit_price,
                        'quantity' => $quantity,
                        'sell_unit' => $sell_unit,
                        'cost' => $cost
                    ];
                }
            }

            if ($total > 0) {
                echo '<div id="bill">';
                echo "<h4>Vintage Flavor-Rajkot Mo: 9724208914</h4>";
                echo "<h5>Bill Details:</h5>";
                echo "<table>";
                echo "<tr><th>Flavour</th><th>PricexQuantity</th><th>Sell Unit</th><th>Cost (₹)</th></tr>";
                echo"<tr><td colspan='4'>---------------------------------------------------------</td></tr>";
                foreach ($bill_details as $item) {
                    echo "<tr><td>{$item['flavor']}</td><td>₹{$item['unit_price']} x {$item['quantity']}</td><td>{$item['sell_unit']}</td><td>₹{$item['cost']}</td></tr>";
                }
                echo"<tr><td colspan='4'>---------------------------------------------------------</td></tr>";
                echo "<tr><td colspan='3' class='total'>Total</td><td>₹$total</td></tr>";
                echo "</table>";
                echo '</div>';
                echo '<button onclick="printBill()">Print Bill</button>';
            } else {
                echo "<p>No flavours selected or invalid quantity entered. Please select at least one valid flavour and quantity to generate the bill.</p>";
            }
        }
        ?>
    </div>
</body>
</html>
