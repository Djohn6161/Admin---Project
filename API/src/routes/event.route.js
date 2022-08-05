const express = require('express');
const router = express.Router();

const eventController = require('../controllers/event.controller');
router.get('/', eventController.getEventList);

router.get('/:OrgSlug/events/:EveSlug',eventController.getEventBySlug);

//router.get('/', eventController.attendeelogin);

//router.get('/:OrgSlug/events/:EveSlug',eventController.getEventBySlug);

module.exports = router;