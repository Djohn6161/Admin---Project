const express = require('express');

const app = express();

const port = process.env.PORT || 3000;

app.get('/', (req, res)=>{
    res.send('This is the page for API');
})

const eventRoutes = require('./src/routes/event.route');
app.use('/api/v1/events', eventRoutes);

app.use('/api/v1/organizers', eventRoutes);

//app.use('/api/v1/login', eventRoutes);

app.listen (port, ()=>{
    console.log(`Express is running at port ${port}`);
});