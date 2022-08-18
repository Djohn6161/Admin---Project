const express = require('express');
const app = express();

app.use(express.urlencoded({extended: false}));
app.use(express.json());

const port = process.env.PORT || 3000;

app.get('/', (req, res)=>{
    res.send('This is the page for API');
})

const eventRoutes = require('./src/routes/event.route');
app.use('/api/v1/', eventRoutes);

// const orgRoutes = require('./src/routes/organizer.route');
// const loginRoutes = require('./src/routes/login.route')
// app.use('/api/v1/events', eventRoutes);

// app.use('/api/v1/organizers', orgRoutes);

// app.use('/api/v1/login', loginRoutes);

// app.use('/api/v1/logout', loginRoutes);


app.listen (port, ()=>{
    console.log(`Express is running at port ${port}`);
});