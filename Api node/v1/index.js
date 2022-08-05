const express = require ('express');
const app = express();

// var app = express.createServer(
//     connect.bodyDecoder()
//     );
app. get('/', (req, res) => {
    res.send('Hello world!!!!');
});

app.get('/api/courses', (req, res) => {
    res.send([1,2,3]);
});

app.get('api/courses/:id', (req, res) => {
    res.send(req.params.id);
});
const port = process.env.PORT || 3000;
app.listen(port, () => console.log(`Listineng on port ${port}...`))