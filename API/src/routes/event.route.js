const express = require('express');
const router = express.Router();

const eventController = require('../controllers/event.controller');
router.get('/events', eventController.getEventList);

router.get('/organizers/:EveSlug/events/:OrgSlug',eventController.getEventBySlug)

router.post('/login', eventController.attendeelogin);

router.post('/logout', eventController.attendeelogout);

// router.get('/:OrgSlug/events/:EveSlug',eventController.getEventBySlug);


//router.get('/:OrgSlug/events/:EveSlug',eventController.getEventBySlug);

module.exports = router;