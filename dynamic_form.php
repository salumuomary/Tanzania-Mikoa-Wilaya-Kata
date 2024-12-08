<?php
// Load JSON data from the uploaded file
$data = json_decode(file_get_contents('Combined_Regions.json'), true);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Region, District, Ward Selectors</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            line-height: 1.6;
        }

        form {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #f9f9f9;
        }

        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }

        select, button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #fff;
            font-size: 16px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        #result {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
            color: #333;
        }
    </style>
    <script>
        const data = <?php echo json_encode($data); ?>;

        function updateDistricts() {
            const region = document.getElementById('region').value;
            const districtSelect = document.getElementById('district');
            const wardSelect = document.getElementById('ward');

            districtSelect.innerHTML = '<option value="">Select District</option>';
            wardSelect.innerHTML = '<option value="">Select Ward</option>';

            if (region && data[region]) {
                const districts = Object.keys(data[region].districts);
                districts.forEach(district => {
                    const option = document.createElement('option');
                    option.value = district;
                    option.textContent = district;
                    districtSelect.appendChild(option);
                });
            }
        }

        function updateWards() {
            const region = document.getElementById('region').value;
            const district = document.getElementById('district').value;
            const wardSelect = document.getElementById('ward');

            wardSelect.innerHTML = '<option value="">Select Ward</option>';

            if (region && district && data[region] && data[region].districts[district]) {
                const wards = data[region].districts[district].wards;
                wards.forEach(ward => {
                    const option = document.createElement('option');
                    option.value = ward;
                    option.textContent = ward;
                    wardSelect.appendChild(option);
                });
            }
        }

        function showSelection(event) {
            event.preventDefault();
            const region = document.getElementById('region').value;
            const district = document.getElementById('district').value;
            const ward = document.getElementById('ward').value;
            
            const resultDiv = document.getElementById('result');
            if (region && district && ward) {
                resultDiv.textContent = `Selected Region: ${region}, District: ${district}, Ward: ${ward}`;
            } else {
                resultDiv.textContent = 'Please select all options (Region, District, and Ward).';
            }
        }
    </script>
</head>
<body>
    <h1>Select Region, District, and Ward</h1>

    <form onsubmit="showSelection(event)">
        <label for="region">Region:</label>
        <select id="region" name="region" onchange="updateDistricts()">
            <option value="">Select Region</option>
            <?php foreach ($data as $region => $details): ?>
                <option value="<?php echo htmlspecialchars($region); ?>">
                    <?php echo htmlspecialchars($region); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="district">District:</label>
        <select id="district" name="district" onchange="updateWards()">
            <option value="">Select District</option>
        </select>

        <label for="ward">Ward:</label>
        <select id="ward" name="ward">
            <option value="">Select Ward</option>
        </select>

        <button type="submit">Submit</button>
    </form>

    <div id="result"></div>
</body>
</html>
