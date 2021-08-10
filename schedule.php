
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Session Listing</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/4.0/examples/starter-template/">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">


  </head>
  <body>

    <main role="main" class="container">

        <div class="starter-template">
            <h1>Sessions Listing</h1>
        </div>

        <form id="filtersField">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">Search</span>
                </div>
                <input type="text" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="basic-addon1" name="search_term">
                <div class="input-group-prepend">
                    <input type="submit" class="btn btn-primary" value="Search..."/>
                </div>            
            </div>
            <a href="/schedule.php">Clear filter</a>
        </form>         

        <ul id="groupedData">

        </ul>

    </main><!-- /.container -->


    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"></script>
    
    <script>
        function fetchData(search_term = null){

            var path = '/server.php?route=fetch_sessions&';
            if (search_term != null) {
                path += 'search_term='+search_term;
            }
            
            $('#groupedData').children().remove();

            axios.get(path)
            .then((res)=>{
                console.log('sessions data', res.data);
                var lis = '';
                for(var i = 0; i < res.data.length ; i++) {

                    var users_lis = '';
                    for(var j =0; j < res.data[i].users.length;j++) {
                        users_lis += `
                            <li>
                                ${res.data[i].users[j]}
                            </li>                        
                        `;
                    }
                    lis += `
                        <li>
                            ${res.data[i].session_info_formatted}
                            <ul>
                                ${users_lis}
                            </ul>            
                        </li>                    
                    `;
                }
                
                if (res.data && res.data.length > 0) {
                    $('#groupedData').append(lis);
                }
            });
        }

        fetchData();
        
        $('#filtersField').on('submit', function(e){
            e.preventDefault();
            var searchField = $(this).find('[name="search_term"]');
            var search_term = searchField.val();
            fetchData(search_term);            
        });

    </script>

  </body>
</html>
