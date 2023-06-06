<html>
<head>
    <?php require_once('../app/components/header.php');?>
</head>
<body>
    <?php require_once('../app/components/navbar.php');?>
    <div class="container mt-4">
        <div class="container">
            <form method="POST" action="process">
                <?php 
                    $crDiscount = $data['crDiscount'];
                    $val = $crDiscount->getFactor();
                    $used = $crDiscount->getUsed();
                    $id = $crDiscount->getId();
                ?>
                <div class="form-group">
                    <input type="hidden" value="<?php echo $id; ?>" name="id">
                    <!-- Add a hidden input to ensure checkbox value is always sent -->
                    <input type="hidden" name="used" value="off">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="usedCheckbox" name="used" <?php if ($used) echo 'checked'; ?>>
                        <label class="form-check-label" for="usedCheckbox">Marchează ca folosit</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="discountFactor">Factor reducere</label>
                    <input type="range" class="form-range" id="discountFactor" placeholder="Factor reducere" name="factor" min="0" max="1" step="0.1" value="<?php echo $val; ?>">
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
    <?php require_once('../app/components/footer.php');?>   
</body>
</html>
