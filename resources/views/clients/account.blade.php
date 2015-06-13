@extends('clients.layout')

@section('content')
<h1>Your Account Information</h1>
<div class="uk-container uk-container-center uk-margin-top uk-margin-large-bottom">


            <div class="uk-grid" data-uk-grid-margin>
                <div class="uk-width-medium-1-3 uk-text-center">
                    <div class="uk-thumbnail uk-overlay-toggle uk-border-circle">
                        <div class="uk-overlay">
                            <img class="uk-border-circle" width="200" height="200" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAxNi4wLjQsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+DQo8c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkViZW5lXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4Ig0KCSB3aWR0aD0iMjAwcHgiIGhlaWdodD0iMjAwcHgiIHZpZXdCb3g9IjAgMCAyMDAgMjAwIiBlbmFibGUtYmFja2dyb3VuZD0ibmV3IDAgMCAyMDAgMjAwIiB4bWw6c3BhY2U9InByZXNlcnZlIj4NCjxyZWN0IGZpbGw9IiNGNUY1RjUiIHdpZHRoPSIyMDAiIGhlaWdodD0iMjAwIi8+DQo8Zz4NCgk8cGF0aCBmaWxsPSIjRDhEOEQ4IiBkPSJNMTgyLjI1NiwxNjUuNzk2Yy0wLjgzNi00LjY3Ny0xLjg5Ni05LjAwNy0zLjE3Mi0xMy4wMDFjLTEuMjc3LTMuOTk2LTIuOTk1LTcuODg4LTUuMTU0LTExLjY4Ng0KCQljLTIuMTU4LTMuNzkzLTQuNjMxLTcuMDI4LTcuNDI3LTkuNzA1Yy0yLjgwMS0yLjY3NC02LjIxMy00LjgxMi0xMC4yNDctNi40MDljLTQuMDM1LTEuNTk3LTguNDg4LTIuMzk2LTEzLjM1OS0yLjM5Ng0KCQljLTAuNzE5LDAtMi4zOTYsMC44NTgtNS4wMzIsMi41NzNjLTIuNjM2LDEuNzIyLTUuNjEyLDMuNjM4LTguOTI3LDUuNzVjLTMuMzE2LDIuMTE4LTcuNjMxLDQuMDM1LTEyLjk0LDUuNzUzDQoJCWMtNS4zMTIsMS43MTktMTAuNjQ2LDIuNTc2LTE1Ljk5NiwyLjU3NmMtNS4zNTIsMC0xMC42ODQtMC44NTctMTUuOTk2LTIuNTc2Yy01LjMxNC0xLjcxOC05LjYyOS0zLjYzNS0xMi45NC01Ljc1Mw0KCQljLTMuMzE5LTIuMTEyLTYuMjkxLTQuMDI4LTguOTI3LTUuNzVjLTIuNjM2LTEuNzE1LTQuMzEyLTIuNTczLTUuMDMzLTIuNTczYy00Ljg3NiwwLTkuMzI5LDAuNzk5LTEzLjM2MSwyLjM5Ng0KCQljLTQuMDMzLDEuNTk4LTcuNDUxLDMuNzM1LTEwLjI0Miw2LjQwOWMtMi44MDEsMi42NzctNS4yNzMsNS45MTItNy40Myw5LjcwNWMtMi4xNTcsMy43OTgtMy44NzcsNy42ODgtNS4xNTMsMTEuNjg2DQoJCWMtMS4yNzgsMy45OTQtMi4zMzcsOC4zMjQtMy4xNzcsMTMuMDAxYy0wLjgzNyw0LjY3MS0xLjM5OCw5LjAyNC0xLjY3NywxMy4wNmMtMC4yNzksNC4wMzMtMC40MTksOC4xNy0wLjQxOSwxMi4zOTkNCgkJYzAsMy4xNCwwLjM0NSw2LjA0LDAuOTY5LDguNzQ1aDE2Ni43NzFjMC42MjUtMi43MDUsMC45NzItNS42MDUsMC45NzItOC43NDVjMC00LjIyOS0wLjE0MS04LjM2Ni0wLjQyMi0xMi4zOTkNCgkJQzE4My42NTQsMTc0LjgyLDE4My4wOTYsMTcwLjQ2NywxODIuMjU2LDE2NS43OTZ6Ii8+DQoJPHBhdGggZmlsbD0iI0Q4RDhEOCIgZD0iTTEwMCwxMzAuMjY4YzEyLjcsMCwyMy41NDQtNC40OTQsMzIuNTMzLTEzLjQ3OWM4Ljk4NC04Ljk4OCwxMy40NzktMTkuODMsMTMuNDc5LTMyLjUzMg0KCQljMC0xMi43MDItNC40OTQtMjMuNTQzLTEzLjQ3OS0zMi41MzFDMTIzLjU0NCw0Mi43MzgsMTEyLjcsMzguMjQzLDEwMCwzOC4yNDNzLTIzLjU0Nyw0LjQ5NS0zMi41MzEsMTMuNDgxDQoJCWMtOC45ODksOC45ODgtMTMuNDgxLDE5LjgyOS0xMy40ODEsMzIuNTMxYzAsMTIuNzAyLDQuNDkyLDIzLjU0NCwxMy40ODEsMzIuNTMyQzc2LjQ1MywxMjUuNzczLDg3LjMsMTMwLjI2OCwxMDAsMTMwLjI2OHoiLz4NCjwvZz4NCjwvc3ZnPg0K" alt="">
                            <div class="uk-overlay-area uk-border-circle">
                                <div class="uk-overlay-area-content">
                                    <a href="#"> Edit Picture</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h2 class="uk-margin-bottom-remove"><?php echo $client['client_name']; ?></h2>
                    
                    <p class="uk-text-large uk-margin-top-remove uk-text-muted"><?php echo $client['client_email'] ?></p>
                    <p><a href="/clients/editaccount"><button class="uk-button uk-button-primary">Edit Account</button></a></p>
                </div>
                
                <div class="uk-width-medium-2-3 uk-float-right">
                    <div class="uk-panel uk-panel-box uk-panel-box-secondary">
                        <h3 class="uk-panel-title"><div class="fa fa-edit" href="">&nbsp;</div> Overview </h3> 
                        
                        <p>
                            <strong>Description</strong>
                            <br><?php echo $client['client_desc']; ?>
                        </p>
                        <p>
                            <strong>Address</strong>
                            <br><?php echo $client['client_addr']; ?>
                            <br>
                            
                        </p>
                        <p>
                            <strong>Phone</strong>
                            <br><?php echo $client['client_telephone']; ?>
                        </p>
                        
                        <p>
                            <strong>Home page</strong>
                            <br><?php echo $client['client_website']; ?>
                        </p>
                        
                        <p>
                            <strong>Email</strong>
                            <br><?php echo $client['client_email']; ?>
                        </p>
                        
<!--                        <h3 class="uk-h4">Social Network</h3>
                        <p>
                            <a href="#" class="uk-icon-button uk-icon-github"></a>
                            <a href="#" class="uk-icon-button uk-icon-twitter"></a>
                            <a href="#" class="uk-icon-button uk-icon-dribbble"></a>
                            <a href="#" class="uk-icon-button uk-icon-html5"></a>
                        </p>-->
                    </div>
                </div>
            </div>

        </div>

@stop
