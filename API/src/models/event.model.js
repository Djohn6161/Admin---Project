const { text } = require('body-parser');
var dbConn = require('../../config/db');
// const Organizer = function(organizer){
//     this
// }
var Event = function (event) {
    this.organizer_id = event.eorganizer_id;
    this.names = event.ename;
    this.slug = event.eslug;
    this.date = event.edate;
}
var arrres = [];
Event.getAllEvents = (result) => {
    dbConn.query('SELECT e.id as eid, e.name as ename, e.slug as eslug, e.date as edate, org.id as orgid, org.name as orgname, org.slug as orgslug FROM events as e, organizers as org where org.id = e.organizer_id', (err, res) => {
        if (err) {
            console.log('Error while fetching Events', err);
            result(null, err);
        } else {
            dbConn.query('SELECT count(id) as counter FROM events', (Cerr, Cres) => {
                if (Cerr) {
                    console.log('Error while fetching Events', Cerr);
                    result(null, Cerr);
                } else {
                    var eventorg = [];
                    for (let i = 0; i < Cres[0]['counter']; i++) {
                        var Events = {
                            "id": res[i]['eid'],
                            "name": res[i]['ename'],
                            "slug": res[i]['eslug'],
                            "date": res[i]['edate'],
                            "organizer": {
                                "id": res[i]['orgid'],
                                "name": res[i]['orgname'],
                                "slug": res[i]['orgslug']
                            }
                        };
                        eventorg.push(Events);
                    }
                }
                result(null, { "Events": eventorg });
            })
        }
    })
}
Event.attendeelogin = (result) => {
    dbConn.query(`SELECT * FROM attendees WHERE attendees.registration_code = '35DGZX'`, (atterr, attres) => {
        let attleng = attres.length;
        if (atterr) {
            console.log('Error while organizers by organizer slug', atterr);
            result(null, atterr);
        }
        else {
            console.log('theb attendee is this')
            result(null, attres);
        }
    })
}
Event.getEventBySlug = (EveSlug, OrgSlug, result) => {
    //let ORGDres = EDres = [];
    dbConn.query(`SELECT * FROM organizers WHERE organizers.slug = ?`, OrgSlug, (ORGDerr, ORGDres) => {
        let Orgleng = ORGDres.length;
        if (ORGDerr) {
            console.log('Error while organizers by organizer slug', ORGDerr);
            result(null, ORGDerr);
        }
        else {
            if (Orgleng >= 1) {
                let events = [];
                dbConn.query(`SELECT events.id, events.name, events.slug, events.date FROM events, organizers WHERE organizers.id = events.organizer_id AND organizers.slug = '${OrgSlug}' AND events.slug = ?`, EveSlug, (EDerr, EDres) => {
                    let Eveleng = EDres.length;
                    if (EDerr) {
                        console.log('Error while fetching channels by channel id', EDerr);
                        result(null, EDerr);
                    }
                    else {
                        console.log("sdasda", Eveleng);
                        if (Eveleng == 1) {
                            let v = 0;
                            let event = {
                                "id": EDres[0].id,
                                "name": EDres[0].name,
                                "slug": EDres[0].slug,
                                "date": EDres[0].date,
                                "channels": "",
                                "tickets": ""
                            }
                            events.push(event);
                            let channels = [];
                            dbConn.query(`SELECT channels.id, channels.name FROM channels WHERE channels.event_id = ?`, EDres[0].id, (CDerr, CDres) => {
                                let CDarrleng = CDres.length;
                                if (CDerr) {
                                    console.log('Error while fetching channels by channel id', CDerr);
                                    result(null, CDerr);
                                }
                                else {
                                    for (let z = 0; z < CDarrleng; z++) {
                                        let channel = {
                                            "id": CDres[z].id,
                                            "name": CDres[z].name,
                                            "rooms": ""
                                        }
                                        channels.push(channel);
                                        let rooms = [];
                                        dbConn.query(`SELECT rooms.id, rooms.name FROM rooms WHERE rooms.channel_id = ?`, CDres[z].id, (RDerr, RDres) => {
                                            let RDarrleng = RDres.length;
                                            if (RDerr) {
                                                console.log('Error while fetching rooms by channel id', RDerr);
                                                result(null, RDerr);
                                            }
                                            else {
                                                for (let x = 0; x < RDarrleng; x++) {
                                                    let room = {
                                                        "id": RDres[x].id,
                                                        "name": RDres[x].name,
                                                        "sessions": ""
                                                    };
                                                    rooms.push(room);
                                                    let sessions = [];
                                                    dbConn.query(`SELECT sessions.id, sessions.title, sessions.description, sessions.speaker, sessions.start, sessions.end, sessions.type, sessions.cost FROM sessions WHERE sessions.room_id = ?`, RDres[x].id, (SDerr, SDres) => {
                                                        var arrleng = SDres.length;
                                                        if (SDerr) {
                                                            console.log('Error while fetching session by room id', SDerr);
                                                            result(null, SDerr);
                                                        } else {
                                                            for (let i = 0; i < arrleng; i++) {
                                                                let session = {
                                                                    "id": SDres[i].id,
                                                                    "title": SDres[i].title,
                                                                    "description": SDres[i].description,
                                                                    "speaker": SDres[i].speaker,
                                                                    "start": SDres[i].start,
                                                                    "end": SDres[i].end,
                                                                    "type": SDres[i].type,
                                                                    "cost": SDres[i].cost,
                                                                };

                                                                sessions.push(session);


                                                            }
                                                            rooms[x].sessions = sessions;
                                                            channels[z].rooms = rooms;
                                                            events[0].channels = channels;
                                                            if (z == CDarrleng - 1) {
                                                                let tickets = [];
                                                                dbConn.query(`SELECT event_tickets.id, event_tickets.name, event_tickets.cost, event_tickets.special_validity FROM event_tickets WHERE event_tickets.event_id = ?`, EDres[0].id, (ETDerr, ETDres) => {
                                                                    let ETDarrleng = ETDres.length;
                                                                    if (ETDerr) {
                                                                        console.log('Error while fetching channels by channel id', ETDerr);
                                                                        result(null, ETDerr);
                                                                    }
                                                                    else {
                                                                        for (let b = 0; b < ETDarrleng; b++) {
                                                                            let ticket = {
                                                                                "id": ETDres[b].id,
                                                                                "name": ETDres[b].name,
                                                                                "description": ETDres[b].special_validity,
                                                                                "cost": ETDres[b].cost,
                                                                                "available": "TRUE"
                                                                            }
                                                                            // console.log("the cost ", ETDres[i].cost)
                                                                            // result(null,ETDres[i]);
                                                                            if (ETDres[b].special_validity != null) {
                                                                                let SV = ETDres[b].special_validity;
                                                                                let check129 = SV.lastIndexOf("date");
                                                                                if (check129 >= 0) {
                                                                                    console.log("here1", SV);
                                                                                    SV = SV.slice(23, 33);
                                                                                    ticket.description = SV;

                                                                                    //32 - 22
                                                                                }
                                                                                else {
                                                                                    console.log("here2", SV);
                                                                                    let SVL = SV.length;
                                                                                    SV = SV.slice(26, SVL - 1)
                                                                                    ticket.description = SV;
                                                                                    //console.log("KIller",SVL);
                                                                                }

                                                                            } else {

                                                                                //ticket.description = NULL;
                                                                                console.log("here3", ticket.description);
                                                                                //console.log("Survice",ETDres[i].special_validity);
                                                                            }
                                                                            tickets.push(ticket);
                                                                        }
                                                                        events[0].tickets = tickets;
                                                                        result(null, events);
                                                                        // else{
                                                                        //     result(null,ETDres);
                                                                        // }
                                                                        // if(typeof check.foo !== 'undefined'){
                                                                        //     //result(null, check);
                                                                        // }
                                                                        //SV.push(ETDres[1].special_validity);
                                                                        //SV.replace(/\"/i, " ");
                                                                        // if(SV.includes("amount")){

                                                                        // }
                                                                        // else{

                                                                        // }

                                                                    }
                                                                })
                                                            }

                                                        }
                                                    })

                                                }
                                            }
                                        })

                                    }

                                }
                            })
                        }
                        else {
                            console.log("Event not found");
                            const message = {
                                "message": "Event not found"
                            }
                            result(null, message);
                        }
                    }
                })
            }
            else {
                console.log("Organizer not found");
                const message = {
                    "message": "organizer not found"
                }
                result(null, message);
            }

        }
    })
}

module.exports = Event;
