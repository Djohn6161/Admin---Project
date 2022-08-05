const express = require('express');
const bodyParser = require('body-parser');
//create express app
const app = express();

//setup the server port
const port = process.env.PORT || 5000;

//Parse request data content type application/x-www-form-rulencoded
app.use(bodyParser.urlencoded({extended:false}));

//parse request data content type application/json
app.use(bodyParser.json());

// define root server
app.get ('/', (req, res) =>{
    res.send('Hello World');
});

// import employee
const employeeRoutes = require('./src/routes/employee.route');

//create employee routes
app.use('/api/v1/employee', employeeRoutes);



//listen to the port
app.listen(port, ()=>{
    console.log(`Server is running at port ${port}`);
});