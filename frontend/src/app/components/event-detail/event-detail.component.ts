/**
 * Created by RaquelMarcos on 9/7/19.
 */
import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute, Params } from '@angular/router';
import {TranslateService} from '@ngx-translate/core';

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
        private _userService: UserService,
        private  _translate: TranslateService

    ) {
        _translate.setDefaultLang('es');

    }

    ngOnInit() {
        this.getEvent();
    }

    getEvent(){
        this._route.params.subscribe(params =>{
            let id_event = params['id'];
            this._eventService.viewEvent(id_event).subscribe(
                response => {

                    this.event = response as Event;

                    if(isUndefined(this.event)){
                        this._router.navigate(['allEvents']);
                    }

                },
                error => {
                    this._router.navigate(['allEvents']);
                }
            );
        });
    }

    useLanguage(language: string) {
        this._translate.use(language);
    }

}
