const express = require('express');
const router = express.Router();

const eventController = require('../controllers/event.controller');
router.get('/events', eventController.getEventList);

router.get('/organizers/:OrgSlug/events/:EveSlug',eventController.getEventBySlug);

router.post('/login', eventController.attendeelogin);

router.post('/logout', eventController.attendeelogout);

router.post('/organizers/:OrgSlug/events/:EveSlug/registration', eventController.eventRegistration);

// router.get('/:OrgSlug/events/:EveSlug',eventController.getEventBySlug);


//router.get('/:OrgSlug/events/:EveSlug',eventController.getEventBySlug);

module.exports = router;