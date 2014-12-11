<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mybanker - Leads</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
    <header>
        <nav class="navbar navbar-inverse navbar-fixed-top" id="main-navbar" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">Mybanker Leads</a>
                </div>

                <div class="collapse navbar-collapse" id="navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li><a href="#" data-toggle="modal" data-target="#newLeadModal">Create New Lead</a></li>
                    </ul>
                    <form id="searchForm" class="navbar-form navbar-right" role="search">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Search">
                        </div>
                        <button type="submit" class="btn btn-default">Submit</button>
                    </form>
                </div>
            </div>
        </nav>
    </header>

    <div class="container">
        <div class="modal fade" id="newLeadModal" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Create a Profile in Our System</h4>
                    </div>

                    <div class="modal-body">
                        <form action="/leads" id="newLeadForm" role="form">
                            <div class="form-group">
                                <label for="name">Name:</label>
                                <input type="text" id="name" name="name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="email">E-Mail:</label>
                                <input type="email" id="email" name="email" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone:</label>
                                <input type='tel' id="phone" name="phone" pattern="^(\+45)? ?\d{2} ?\d{2} ?\d{2} ?\d{2}$" title="Phone Number (Format: +45 99 99 99 99)" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="address">Address:</label>
                                <input type="text" id="address" name="address" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="postal_code">Post Nr:</label>
                                <input type="number" min="1000" max="9999" id="postal_code" name="postal_code" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="city">City:</label>
                                <input type="text" id="city" name="city" class="form-control" required>
                            </div>
                            <div class="newsletter-options">
                                <div class="form-group">
                                    <label for="newsletter">Newsletter:</label>
                                    <input type="checkbox" id="newsletter" name="newsletter" value="Yes" checked>
                                </div>
                                <div class="preferred_format">
                                    <div class="form-group">
                                        <label for="newsletter_format">Format:</label>
                                        <input type="radio" checked="checked" id="newsletter_format" name="newsletter_format" value="html"> HTML
                                        <input type="radio" id="newsletter_format" name="newsletter_format" value="text"> Text
                                    </div>
                                </div>
                            </div>

                            <input type="submit" class="btn btn-success" value="Sign up" />
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">

        <div id="result" class"row"></div>
        <div id="links" class"row"></div>
        <div id="leads" class"row"></div>

    </div>

    <template id="lead-template">
        <div class="panel-wrapper col-md-6 col-lg-4">
            <div class="panel panel-default panel-custom" data-id="{{id}}">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <strong>Name:</strong>
                        <span class="noedit name">{{name}}</span>
                        <input type="text" name="name" class="edit name form-control" required>
                    </h4>
                </div>

                <div class="panel-body">
                    <p>
                        <strong>Email:</strong>
                        <span class="noedit email">{{email}}</span>
                        <input type="email" name="email" class="edit email form-control" required>
                    </p>

                    <p>
                        <strong>Phone:</strong>
                        <span class="noedit phone">{{phone}}</span>
                        <input type='tel' name="phone" pattern="^(\+45)? ?\d{2} ?\d{2} ?\d{2} ?\d{2}$" title="Phone Number (Format: +45 99 99 99 99)" class="edit phone form-control" required>
                    </p>

                    <p>
                        <strong>Address:</strong>
                        <span class="noedit address">{{address}}</span>
                        <input type="text" name="address" class="edit address form-control" required>
                    </p>

                    <p>
                        <strong>Post Nr:</strong>
                        <span class="noedit postal_code">{{postal_code}}</span>
                        <input type="number" name="postal_code" min="1000" max="9999" class="edit postal_code form-control" required>
                    </p>

                    <p>
                        <strong>City:</strong>
                        <span class="noedit city">{{city}}</span>
                        <input type="text" name="city" class="edit city form-control" required>
                    </p>

                    <p>
                        <div class="newsletter-options">
                            <strong>Newsletter:</strong>
                            <span class="noedit newsletter">{{newsletter}}</span>
                            <input type="checkbox" name="newsletter" class="edit newsletter" value="Yes">
                            <div class="preferred_format">
                                <em>Preferred Format:</em>
                                <input type="radio" name="newsletter_format" class="edit newsletter_format" value="html" checked="checked"> HTML
                                <input type="radio" name="newsletter_format" class="edit newsletter_format" value="text"> Text
                            </div>
                        </div>
                    </p>
                </div>

                <div class="panel-footer">
                    <button class="btn btn-primary editLead noedit">Edit</button>
                    <button class="btn btn-success saveEdit edit">Save</button>
                    <button data-id="{{id}}" class="btn btn-danger noedit remove">Delete</button>
                    <button class="btn btn-info cancelEdit edit">Cancel</button>
                </div>
            </div>
        </div>
    </template>


    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
    <script src="js/mustache.js"></script>
</body>
</html>
