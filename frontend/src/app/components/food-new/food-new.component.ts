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
  private page_title: string;
  private identity;
  public food: Food;
  public status: string;

  private errors = {};

  constructor(
      private _route: ActivatedRoute,
      private _router: Router,
      private _userService: UserService,
      private _foodService: FoodService
  ) {
      this.page_title = 'Create new food';
      this.identity = this._userService.getIdentity();
  }

  ngOnInit() {
    if(this.identity == null){
      this._router.navigate(["/login"]);
    }
  }

  onSubmit(form){
    //console.log(this._userService.getIdentity());
    //console.log(this.food);
    //this.food.restaurant = this._userService.identity.id_user;
    console.log(form.value);
    this._foodService.createFood(form.value).subscribe(
        response=>{
          console.log(response);
          this.status = 'success';
          this._router.navigate(['/allFoods']);
        },
        error=>{
          console.log(<any> error);
          this.status = 'error';
          this.errors = error.error;
        }
    );
  }

}
