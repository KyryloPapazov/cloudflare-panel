<h1>This is web application for manage cloudflare accounts, domains and Page rules</h1>
<p>This project create in Laravel v11.21.0 (PHP v8.3.10)</p> <br>

 <p>for install this application into your machine, you must have:</p>
            <ul>ubuntu with <a href="https://www.digitalocean.com/community/tutorials/how-to-install-lamp-stack-on-ubuntu"></a> LAMP or :
                <li> <a href="https://www.geeksforgeeks.org/how-to-install-php-on-ubuntu/">PHP version 8.1+ </a> (<span style="color: red;">tutorial in link</span> )</li>
                <li> <a href="https://www.digitalocean.com/community/tutorials/how-to-install-and-use-composer-on-ubuntu-20-04">Composer </a>(<span style="color: red;">tutorial in link</span> ) </li>
                <li> <a href="https://www.digitalocean.com/community/tutorials/how-to-install-node-js-and-create-a-local-development-environment-on-windows">Node.js </a>(<span style="color: red;">tutorial in link</span> ) </li>
                <li> <a href="https://www.digitalocean.com/community/tutorials/how-to-install-mysql-on-ubuntu-22-04">any relational database </a> (<span style="color: red;">I used MySQL with MySQL Workbench - not necessarily</span> ) </li>
                <li> <a href="https://git-scm.com/downloads">Git </a> (<span style="color: red;">Link</span> ) </li>
            </ul>
            <ul>windows with <a href="https://github.com/OSPanel/OpenServerPanel/wiki/%D0%94%D0%BE%D0%BA%D1%83%D0%BC%D0%B5%D0%BD%D1%82%D0%B0%D1%86%D0%B8%D1%8F">Open Server</a> or:
                <li> <a href="https://www.geeksforgeeks.org/how-to-install-php-in-windows-10/">PHP version 8.1+ </a> (<span style="color: red;">tutorial in link</span> )</li>
                <li> <a href="https://www.geeksforgeeks.org/how-to-install-php-composer-on-windows/">Composer</a>  (<span style="color: red;">tutorial in link</span> )</li>
                <li> <a href="https://www.digitalocean.com/community/tutorials/how-to-install-node-js-on-ubuntu-22-04">Node.js </a>(<span style="color: red;">tutorial in link</span> ) </li>
                <li> <a href="https://www.geeksforgeeks.org/how-to-install-mysql-in-windows/">any relational database</a> (<span style="color: red;">I used MySQL with MySQL Workbench - not necessarily</span> ) </li>
                <li> <a href="https://git-scm.com/downloads">Git</a> (<span style="color: red;">Link</span> ) </li>
            </ul>
            <h2>If this has already been established, you can start cloning the project</h2>
            <p>This is link for clone my Git repository: <a href="https://github.com/KyryloPapazov/cloudflare-panel.git">https://github.com/KyryloPapazov/cloudflare-panel.git</a></p> 

            <h1>Install into windows</h1>

            <h3>1. Create and go to directory for project</h3>
            <p>Open terminal into windows(win + r -> cmd.exe) and run command:</p>
            <p><b>important!</b> you you need to know which folder you are working in and set it to the right one.(<span style="color: red;">NOT SYSTEM32!</span>)</p>
            <address>mkdir name_project</address> 
            <p>and go to new directory</p>
            <address>cd name_project</address> 

            <h3>2. Make git clone repository</h3>
            <address>git clone https://github.com/KyryloPapazov/cloudflare-panel.git</address> 

            <h3>3. After cloning need go to direcrory cloudflare-panel and install relationships</h3>
            <address>cd cloudflare-panel</address> 
            <address>npm install</address> 
            <address>composer install</address> 
            <p>If you get an error, check if php with its dependencies and npm are installed</p>
            <p>Maybe in cmd the commands won't work, try using powershell to perform these steps</p>

            <h3>4. after downloading the dependencies, you need to create an .env file</h3>
            <address>php artisan key:generate</address>
            <p>If not complete generate APP KEY and .env file </p>
            <label>Copy file to .env</label>
            <address>cp .env.example .env</address> 
            <br>
            <label>Gen APP KEY </label>
            <address>php artisan key:generate</address> 

            <h3>5. Connect to DataBase in .env</h3>
            <address>DB_CONNECTION=sqlite #mysql</address>
            <p>If DB not SQLite, fill in the following fields</p>
            <address>#DB_HOST=127.0.0.1 </address>
            <address>#DB_PORT=</address>
            <address>#DB_DATABASE=</address>
            <address>#DB_USERNAME=</address>
            <address>#DB_PASSWORD=</address>
            
            <h3>6. Run migrations</h3>
            <address>php artisan migrate</address>


            <h3>7. If previous steps full completed, have to run servers for laravel and vue.js</h3>
            <p>For this run commands in directory project(your/path/cloudflare-panel):</p>
            <label>run server vue.js</label>
            <address>npm run dev</address> 
            <label>run server laravel</label>
            <address>php artisan serve</address> 


            <h1>Good Work! In <a href="http://localhost:8000">www.localhost:8000</a> my project</h3>

            <h1>Install into ubuntu</h1>

            <h3>1. Create and go to directory for project</h3>
            <p>Open terminal into windows(CTRL+ALT+T) and run command:</p>
            <p><b>important!</b> you you need to know which folder you are working in and set it to the right one.</p>
            <p><b>important!</b> you may have to use “<span style="color: red;">sudo</span>”, use it carefully!.</p>
            <address>mkdir name_project</address> 
            <p>and go to new directory</p>
            <address>cd name_project</address> 

            <h3>2. Make git clone repository</h3>
            <address>git clone https://github.com/KyryloPapazov/cloudflare-panel.git</address> 

            <h3>3. After cloning need go to direcrory cloudflare-panel and install relationships</h3>
            <address>cd cloudflare-panel</address> 
            <br>
            <address>npm install</address> 
            <br>
            <address>composer install</address> 
            <p>If you get an error, check if php with its dependencies and npm are installed</p>
            <p>Maybe in cmd the commands won't work, try using powershell to perform these steps</p>

            <h3>4. after downloading the dependencies, you need to create an .env file</h3>
            <address>php artisan key:generate</address>
            <p>If not complete generate APP KEY and .env file </p>
            <label>Copy file to .env</label>
            <address>cp .env.example .env</address> 
            <br>
            <label>Gen APP KEY </label>
            <address>php artisan key:generate</address> 

            <h3>5. Connect to DataBase in .env</h3>
            <address>DB_CONNECTION=sqlite #mysql</address>
            <p>If DB not SQLite, fill in the following fields</p>
            <address>#DB_HOST=127.0.0.1 </address>
            <address>#DB_PORT=</address>
            <address>#DB_DATABASE=</address>
            <address>#DB_USERNAME=</address>
            <address>#DB_PASSWORD=</address>
            
            <h3>6. Run migrations</h3>
            <address>php artisan migrate</address>
           
            <h3>7. If previous steps full completed, have to run servers for laravel and vue.js</h3>
            <p>For this run commands in directory project(your/path/cloudflare-panel):</p>
            <label>run server vue.js</label>
            <address>npm run dev</address> 
            <p></p>
            <label>run server laravel</label>
            <address>php artisan serve</address> 


            <h1>Good Work! In <a href="http://localhost:8000">www.localhost:8000</a> my project</h3>
