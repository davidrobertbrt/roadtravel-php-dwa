<html>
    <head>
        <?php require_once('../app/components/header.php');?>
    </head>
    <body>
        <?php require_once('../app/components/navbar.php');?>
                    <div class = "container mt-4">
                    <div class="container mt-5">
                <div class="row">
                <div class="col-md-6">
                    <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Contact Information</h5>
                        <p><strong>Phone:</strong> +1 123-456-7890</p>
                        <p><strong>Address:</strong> 123 Main St, City, Country</p>
                        <p><strong>Email:</strong> example@example.com</p>
                    </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Contact Form</h5>
                        <form action = "./../contact/process" method="POST">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" placeholder="Enter your name" name = "name" required>
                        </div>
                        <div class="form-group">
                            <label for="subject">Subject</label>
                            <input type="text" class="form-control" id="subject" placeholder="Enter the subject" name = "subject" required>
                        </div>
                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea class="form-control" id="message" rows="5" placeholder="Enter your message" name="message" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
        <?php require_once('../app/components/footer.php');?>
    </body>
</html>
