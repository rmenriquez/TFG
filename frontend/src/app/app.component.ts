import { Component, OnInit, DoCheck } from '@angular/core';
import { UserService } from './services/user.service';
import { FoodService } from './services/food.service';


@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css'],
  providers: [UserService, FoodService]
})
export class AppComponent implements OnInit, DoCheck{
  title = 'frontend';
  public identity;
  public token;

  constructor(
      private _userService: UserService
  ){
      this.identity = this._userService.getIdentity();
      this.token = this._userService.getToken();

  }

  ngOnInit(){
        console.log('app.component cargado');
  }

  ngDoCheck(){
    this.identity = this._userService.getIdentity();
    this.token = this._userService.getToken();
  }
}
