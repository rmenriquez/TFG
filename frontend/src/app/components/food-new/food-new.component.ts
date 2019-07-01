import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute, Params } from '@angular/router';
import { UserService } from '../../services/user.service';
import { Food } from '../../models/food';
import { FoodService } from '../../services/food.service';

@Component({
  selector: 'app-food-new',
  templateUrl: './food-new.component.html',
  styleUrls: ['./food-new.component.css'],
  providers: [UserService, FoodService]
})
export class FoodNewComponent implements OnInit {
  public page_title: string;
  public identity;
  public token;
  public food: Food;

  constructor(
      private _route: ActivatedRoute,
      private _router: Router,
      private _userService: UserService,
      private _foodService: FoodService
  ) {
      this.page_title = 'Create new food';
      this.identity = this._userService.getIdentity();
      this.token = this._userService.getToken();
  }

  ngOnInit() {
    if(this.identity == null){
      this._router.navigate(["/login"]);
    }else{
      //Crear objeto food
      this.food = new Food(1, '', '', '', 0, 0);
    }
  }

  onSubmit(form){
    console.log(this._userService.identity);
    console.log(this._userService.getIdentity());
    console.log(this.food);
    this.food.restaurant = this._userService.identity.id_user;
    console.log(this.food);
    this._foodService.createFood(this.food, this._userService.getIdentity()).subscribe(
        response=>{
          console.log(response);
        },
        error=>{
          console.log(<any> error);
        }
    );
  }

}
