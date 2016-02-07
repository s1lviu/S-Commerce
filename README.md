<h2 id="s-commerce-e-commerce-platform">S-Commerce: e-commerce platform</h2>

<p>S-commerce is a web e-commerce platform based on PHP, MongoDB, JQuery and HTML5 localstorage <br>
This is a nigthly version and could be buggy.</p>

<p>Live demo: <a href="http://silviu-s.com/proiecte/mongo">S-Commerce Live Demo</a></p>
<p>Author contact: <a href="http://silviu-s.com/contact/">http://silviu-s.com/contact/</a></p>


<h2 id="s-commerce-the-project-description">S-Commerce: the project description</h2>



<h2 id="project-scructure">Project structure:</h2>

<p><img src="http://i.imgur.com/VqNfzNm.png" alt="enter image description here" title=""></p>

<p>The main site: <br>
<code>index.php</code> - The main site with product listing and search function (by title or description) . <br>
<code>account.php</code> - There is processed the information about account: register, login, address, orders and order details. <br>
<code>cart.php</code> - Contains the products added in shopping cart, with empty cart function, checkout functions and with informations about total cost of the products added . <br>
<code>db.php</code> - Here is the connection to the database server</p>

<p>Resources: <br>
<code>resources</code> directory which contains the CSS and JavaScript files for a clean and logic structure  <br>
<code>uploads</code> folder which contains the products images</p>

<p>Administration: <br>
<code>admin</code> directory contains the <code>index.php</code> file which is responsable with adding products in database and <code>manage_products.php</code> a sistem which implement the <a href="https://en.wikipedia.org/wiki/Create,_read,_update_and_delete">CRUD</a> functions for products.</p>



<h2 id="databse-structure">Databse structure</h2>

<p><img src="http://i.imgur.com/tTtrkDK.png" alt="enter image description here" title=""></p>

<p>The database contains collections as you could see.  <br>
Theese collections store the information about users, orders and products as JSON-like documents called <a href="https://en.wikipedia.org/wiki/BSON">BSON</a> <br>
A collection it’s like a SQL table, but because it’s on NoSQL engine it process and deliver the information faster than a SQL database. <br>
The website database’s collections stores the following information:  <br>
<code>users</code>  contains the username, password, e-mail address and phisical address of the user  <br>
<code>products</code> contains title, description, price and name of image associated with the product in order of build the image path  <br>
<code>orders</code> contains the user_id which has placed a order and the ids of the products separated by semicolon</p>

<p>All of these collections contain a unique identifier called <code>_id</code> in order of create the relations between them.</p>
