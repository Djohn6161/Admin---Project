const EventModel = require('../models/event.model');
exports.getEventList = (req, res) => {
    //console.log('here are the event list');
    EventModel.getAllEvents((err, events) =>{
        if(err){
            res.send(err);
            console.log('There is an error in ', __dirname);
        }else{
            console.log('All Events: ', events);
            res.status(200).json(events)
        }
    })
}
//get event by org slug and event slug
exports.getEventBySlug = (req, res)=>{
    console.log(`get Event by event Slug ${req.params.EveSlug} and organizers slug ${req.params.OrgSlug}`);
    EventModel.getEventBySlug(req.params.EveSlug, req.params.OrgSlug, (err, event)=>{
        if(err){
            res.send(err);
            console.log('There is an error in '), __dirname;
        }else{
            if(event.message !== undefined){
                console.log(event.message);
                res.status(404).json(event)
            }
            else{
                console.log('Single Event data ', event);
                res.status(200).json(event);
            }
        }
    })
}
exports.attendeelogin = (req, res) => {
    const { lastname, registrationcode } = req.body;
    EventModel.attendeeInformation(lastname, registrationcode, (err, attendee) => {
        if(err){
            res.send(err);
        }else{
            if(attendee.message !== undefined){
                console.log(attendee.message);
                res.status(404).json(attendee)
            }else{
                console.log(`The lastname is  ${lastname} and registration code is ${registrationcode}`);
                console.log('Attendee data ', attendee);
                res.status(200).json(attendee);
            }
            
        }
    })
}
exports.attendeelogout = (req, res) => {
    const {token} = req.query;
    EventModel.attendeelogout(token, (err, attenout) => {
        if(err){
            res.send(err);
        }else{
            if(attenout.message !== undefined){
                console.log(attenout.message);
                res.status(404).json(attenout)
            }else{
                console.log('the token is', token);
                res.status(200).json(attenout);
            }
        }
    })
}