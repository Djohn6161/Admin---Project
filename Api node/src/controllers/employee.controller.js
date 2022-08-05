const EmployeeModel = require('../models/employee.model');
//get all employee list
exports.getEmployeeList = (req, res) => {
    //console.log('here all employees list');
    EmployeeModel.getAllEmployees((err, employees) =>{
        console.log('We are here');
        if(err)
            res.send(err);
        console.log('Employees', employees);
        res.send(employees);
    })
}
//get employee by ID
exports.getEmployeeByID = (req, res)=>{
    //console.log(`get employee by ID ${req.params.id}`);
    EmployeeModel.getEmployeeByID(req.params.id, (err, employee)=>{
        if(err)
        res.send(err);
        console.log('Single employee data', employee);
        res.send(employee);
    })
}

//create new employeee
exports.createNewEmployee = (req, res) =>{
    const employeeReqData = new EmployeeModel(req.body);
    console.log('employeeReqData', employeeReqData);

    //check null
    if(req.body.contructor === Object && Object.keys(req.body).length === 0){
        req.send(400).send({success: false, message: 'Please fill all fields'});    
    }else{
        console.log('Valid data');
        EmployeeModel.createEmployee(employeeReqData, (err, employee)=>{
            if(err)
                res.send(err);
                res.json({status: true, message: 'Employee created Successfully', data: employee.insertId})
            
        })
    }
}
// update employee
exports.updateEmployee = (req, res)=>{
    const employeeReqData = new EmployeeModel(req.body);
    console.log('employeeReqData update', employeeReqData);
    // check null
    if(req.body.constructor === Object && Object.keys(req.body).length === 0){
        res.send(400).send({success: false, message: 'Please fill all fields'});
    }else{
        EmployeeModel.updateEmployee(req.params.id, employeeReqData, (err, employee)=>{
            if(err)
            res.send(err);
            res.json({status: true, message: 'Employee updated Successfully'})
        })
    }
}
//delete employee 
exports.deleteEmployee = (req, res)=>{
    EmployeeModel.deleteEmployee(req.params.id, (err, employee)=>{
        if(err)
        res.send(err);
        res.json({success:true, message: "Employee deleted Successfully!"});
    })
}