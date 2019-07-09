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
    selector: 'app-event-edit',
    templateUrl: './event-edit.component.html',
    providers: [UserService, EventService]
})
export class EventEditComponent implements OnInit {

    public page_title: string;
    public status: string;

    public event: Event;

    constructor(
        private _route: ActivatedRoute,
        private _router: Router,
        private _eventService: EventService,
        private _userService: UserService
    ) {

    }

    ngOnInit() {
        this._route.params.subscribe(params => {
            console.log(params);
            let id_event = params['id'];
            this.getEvent(id_event);
        });
    }

    getEvent(id_event){
        this._eventService.viewEvent(id_event).subscribe(
            response => {
                console.log(response);

                //console.log('estoy dentro');
                this.event = response;
                this.page_title = 'Editar ' + this.event.type + ' de ' + this.event.name + ' - ' + this.event.date;
                if(isUndefined(this.event)){
                    this._router.navigate(['allEvents']);
                }

            },
            error => {
                console.log(<any> error);
                this._router.navigate(['allEvents']);
            }
        );
    }

    onSubmit(form){
        //Servicio
        console.log(this.event.id_event);
        this._eventService.updateEvent(this.event, this.event.id_event).subscribe(
            response => {
                if(response == null){
                    this.status = 'success';
                    this._router.navigate(['/allEvents']);
                }
                console.log(this.status);

            },
            error => {
                console.log(<any> error);
                this.status = 'error';
            }
        );

    }

}
