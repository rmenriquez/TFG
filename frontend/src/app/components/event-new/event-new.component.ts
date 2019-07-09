/**
 * Created by RaquelMarcos on 05/7/19.
 */
import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute, Params } from '@angular/router';
import { UserService } from '../../services/user.service';
import { Event } from '../../models/event';
import { EventService } from '../../services/event.service';

@Component({
  selector: 'app-event-new',
  templateUrl: './event-new.component.html',
  providers: [UserService, EventService]
})
export class EventNewComponent implements OnInit {

  private page_title: string;
  private identity;
  private event: Event;
  private status: string;

  private errors = {};

  constructor(
      private _route: ActivatedRoute,
      private _router: Router,
      private _userService: UserService,
      private _eventService: EventService
  ) {
    this.page_title = 'Create new event';
    this.identity = this._userService.getIdentity();
  }

  ngOnInit() {
    if(this.identity == null){
      this._router.navigate(["/login"]);
    }
  }

  onSubmit(form){
    console.log(form.value);
    this._eventService.createEvent(form.value).subscribe(
        response => {
          console.log(response);
          this.status = 'success';
          this._router.navigate(['/allEvents']);
        },
        error=> {
          console.log(<any> error);
          this.status = 'error';
          this.errors = error.error;
        }
    );
  }

}
