const articles = document.getElementById("articles"); 

if(articles) 
{ 
    articles.addEventListener('click', e => { 
        
       if(e.target.className === 'btn btn-outline-danger delete-article'){
           
            if(confirm("Are you sure?")){
                const id = e.target.getAttribute('data-id');
                
                fetch('/article/'+id+'/delete', {method: 'GET'}).then(res =>window.location.reload());
            }
       }
    
    }); 
} 
