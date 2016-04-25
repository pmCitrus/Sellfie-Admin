<!DOCTYPE html>
<html lang="en">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-9 main">
                @yield('content')
            </div>
            <div class="col-sm-3 main">
                <div class="panel panel-danger">
                      <div class="panel-heading">
                            <h3 class="panel-title">IMPORTANT! HEADS UP!</h3>
                      </div>
                      <div class="panel-body">
                            <p class="text-center">
                                Since <strong>v6.0</strong>, the package
                                now uses<br> <strong>Yajra\Datatables</strong> (capital <strong>Y</strong>) <br>
                                namespace instead of <br><strong>yajra\Datatables</strong>
                                to match the proper naming convention for vendor name.

                                <br>
                                <br>

                                Users of <strong>v5.x</strong> and below will still be using the old namespace <strong>yajra\Datatables</strong>.

                                <br><br>
                                Thanks! - @yajra
                            </p>
                      </div>
                </div>

                <br>

                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="glyphicon glyphicon-bell"></i> Heads Up!</h3>
                    </div>
                    <div class="panel-body">
                        This package is designed to work side-by-side with <a href="https://datatables.net">DataTables.net</a> jQuery plugin. So make sure to check out the plugin's documentations.

                        <h4>Quick Links</h4>
                        <ul>
                            <li><a href="https://datatables.net/reference/option/ajax">Ajax Options</a></li>
                            <li><a href="https://datatables.net/reference/option/columns">Columns Options</a></li>
                            <li><a href="https://datatables.net/examples/index">Examples</a></li>
                            <li><a href="https://datatables.net/examples/ajax/index.html">Ajax Examples</a></li>
                            <li><a href="https://datatables.net/examples/server_side/">Server-side Processing</a></li>
                            <li><a href="https://datatables.net/extensions/index">Extensions</a></li>
                            <li><a href="https://datatables.net/forums/">Forum</a></li>
                        </ul>
                    </div>
                </div>

                <br>
                @include('donate')

            </div>
        </div>
    </div>
</body>
</html>
