
<!DOCTYPE html>
	<head>
		<title>PetLove</title>
		<script src="js/jquery-1.11.0.min.js"></script>
		<script>

			$(window).ready(function(){
				
				

			});

			$(window).load(function(){
				
			});


			function ajax(){
				$.ajax({
					type:"POST",
					url:"http://samplusil.cafe24.com:8080/petlove/ajax.php",
					data:"key=nuri&seq=1&name=nuri",
					dataType:"json",
					//traditional: true,
					//contentType: "application/x-www-form-urlencoded;charset=utf-8",
					success:function( data ){
						//alert(data[0]["name"]);

						for (var i = 0; 0 < data.length ; i++ )
						{
							alert(data[i]["name"]);
						}
						//console.log("로그인 성공");
						
						/*
						var id = data["resultData"]["sign_id"];
						
						alert("로그인 성공 Id - " +id);
						location.href="login_2?sign_id="+id;
						*/
					
					},
					error:function(request,status,error){
						alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
						//alert(request.responseJSON["resultData"]);
						//alert("에러");
					}
				});
			}

		</script>

	</head>
	<body>

<button onclick="ajax();">ajax</button>

	</body>
</html>
		