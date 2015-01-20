<?php

namespace eclore\userBundle\Timeline;

final class TimelineEvents
{ //dispatch rules:
    //to user1 and user2 contacts OK
  const onContactAck = 'ecloreuser.timeline.contact_ack';
    //to young contacts, its inst instM, Project responsibles. to project. OK
  const onValidatedPA = 'ecloreuser.timeline.validated_PA';
    //to young contacts, its inst instM, Project responsibles OK
  const onNewPA = 'ecloreuser.timeline.new_PA';
    //to young inst instM, Project responsibles OK
  const onRejectedPA = 'ecloreuser.timeline.rejected_PA';
    //to all instM and young that took part in a project of this asso. to project and asso. OK
  const onNewProject = 'ecloreuser.timeline.new_project';
    //to project responsible, instM from young, project OK
  const onMarkedProject = 'ecloreuser.timeline.marked_project';
    //to instM from young OK
  const onMarkedYoung = 'ecloreuser.timeline.marked_young';
    // assoM:to assoM of same asso OK
    // instM:to instM of same inst, youngs of same inst OK
    // young:to young's inst instM and young from inst OK
  const onNewUser = 'ecloreuser.timeline.new_user';
    // when something needs to be validated by admin, send an email
  const onPendingValidation = 'ecloreuser.timeline.pending_validation';
    
}
