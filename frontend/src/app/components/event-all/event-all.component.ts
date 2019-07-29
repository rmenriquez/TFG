/**
 * Created by RaquelMarcos on 9/7/19.
 */
import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute, Params } from '@angular/router';
import {TranslateService} from '@ngx-translate/core';

import { EventService } from '../../services/event.service';

import { UserService } from '../../services/user.service';

import {isUndefined} from "util";

@Component({
    selector: 'app-event-all',
    templateUrl: './event-all.component.html',
    providers: [EventService]
})
export class EventAllComponent implements OnInit {

    public events: any = [];

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
        this.getEvents();
        if (isUndefined(this._userService.getIdentity())) {
            this._router.navigate(["login"]);
        } else {
            this._eventService.getEvents().subscribe(
                response => {
                    this.events = response;
                },
                error => {
                    console.log(<any> error);
                }
            );
        }
    }

    getEvents(){
        this._eventService.getEvents().subscribe(
            response => {
                this.events = response;
            },
            error => {
                console.log(<any> error);
            }
        );
    }

    deleteEvent(id){
        this._eventService.deleteEvent(id).subscribe(
            response => {
                //this._router.navigate['home'];
                this.getEvents();

            },
            error => {

            }
        );
    }

    useLanguage(language: string) {
        this._translate.use(language);
    }
}
