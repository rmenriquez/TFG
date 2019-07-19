import { Component, OnInit, DoCheck } from '@angular/core';
import {TranslateService} from '@ngx-translate/core';

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
      private _userService: UserService,
      private  _translate: TranslateService
  ){
      this.identity = this._userService.getIdentity();
      this.token = this._userService.getToken();
      _translate.setDefaultLang('es');

  }

  ngOnInit(){
        console.log('app.component cargado');
  }

  ngDoCheck(){
    this.identity = this._userService.getIdentity();
    this.token = this._userService.getToken();
  }

  useLanguage(language: string) {
    this._translate.use(language);
  }
}
