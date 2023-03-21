function readCookie()
        {
            userID = null;
            let data = document.cookie;
            let splits = data.split(",");
            for(var i = 0; i < splits.length; i++) 
            {
                let thisOne = splits[i].trim();
                let tokens = thisOne.split("=");

                if( tokens[0] == "userID" )
                {
                    userID = parseInt( tokens[1].trim() );
                    
                }
            }

            if( userID == null || userID < 0 || isNaN(userID) )
            {
                window.location.href = "index.html";
            }
            else
            {
                console.log("logged in as user " + userID);
            }
        }
        readCookie()

        document.getElementById("submit").onclick = async function()
        {
            let firstName = document.getElementById("firstNameInput").value;
            let lastName = document.getElementById("lastNameInput").value;
            let email = document.getElementById("emailInput").value;
            let phone = document.getElementById("phoneInput").value;
            
            response = await API_create(userID, firstName, lastName, email, phone);
            if (response.isError)
            {
                console.log("There was an error");
            }
            else
            {
                console.log("Success")
                window.location.href = "/contactList.html";
            }
        }

        document.getElementById("cancel").onclick = function ()
        {
            window.location.href = "/contactList.html";
        }