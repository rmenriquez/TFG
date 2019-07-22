/**
 * Created by RaquelMarcos on 9/7/19.
 */
import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute, Params } from '@angular/router';
import { EventService } from '../../services/event.service';
import { Event } from '../../models/event';
import { UserService } from '../../services/user.service';
import {isUndefined} from "util";

@Component({
    selector: 'app-event-detail',
    templateUrl: './event-detail.component.html',
    providers: [UserService, EventService]
})
export class EventDetailComponent implements OnInit {

    public event: Event;

    constructor(
        private _route: ActivatedRoute,
        private _router: Router,
        private _eventService: EventService,
        private _userService: UserService
    ) {

    }

    ngOnInit() {
        this.getEvent();
    }

    getEvent(){
        this._route.params.subscribe(params =>{
            console.log(params);
            let id_event = params['id'];
            console.log(id_event);
            this._eventService.viewEvent(id_event).subscribe(
                response => {
                    console.log(response['food'][0]['title']);

                    //console.log('estoy dentro');
                    this.event = response as Event;
                    console.log(this.event);
                    console.log(this.event['food']);
                    if(isUndefined(this.event)){
                        this._router.navigate(['allEvents']);
                    }

                },
                error => {
                    console.log(<any> error);
                    this._router.navigate(['allEvents']);
                }
            );
        });
    }

}
