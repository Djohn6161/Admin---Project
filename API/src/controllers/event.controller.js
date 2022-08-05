const EventModel = require('../models/event.model');
exports.getEventList = (req, res) => {
    //console.log('here are the event list');
    EventModel.getAllEvents((err, events) =>{
        console.log('We are Here');
        if(err)
        res.send(err);
        console.log(events);
        res.send(events)
    })
}
//get employee by ID
exports.getEventBySlug = (req, res)=>{
    console.log(`get Event by Slug ${req.params.EveSlug} ${req.params.OrgSlug}`);
    EventModel.getEventBySlug(req.params.OrgSlug, req.params.EveSlug, (err, event)=>{
        if(err)
        res.send(err);
        console.log('Single Event data', event);
        res.send(event);
    })
}
exports.attendeelogin = (req, res) => {
    console.log(`information login`);
    EventModel.attendeeInformation((err, attendee) => {
        if(err)
        res.send(err);
        console.log('Attendee data', attendee);
        res.send(attendee);

    })
}