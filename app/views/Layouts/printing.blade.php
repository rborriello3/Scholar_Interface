<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    @section('head')
    	@if($table == TRUE)
    		<style type="text/css">
        		td
                {
                    text-align: center;
                }

                .Even {
            		/*background-color: #95B9C7*/
        		}

        		.Odd {
            		/*background-color: #FAEBD7*/
        		}

                .Equal {
                    background-color: yellow;
                }

        		table {
            		border: 1px solid;
        		}

		        tbody td {
            		border: 1px solid;
        		}

		        th {
            		border: 1px solid;
        		}
    		</style>
    	@endif
    @show
</head>
<body>
<div id="container">
    @yield('content')
</div>
</body>
</html>