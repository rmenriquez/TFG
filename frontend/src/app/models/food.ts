/**
 * Created by RaquelMarcos on 14/6/19.
 */
export class Food{
    constructor(
        public id_food: number,
        public title: string,
        public description: string,
        public image: string,
        public restaurant: number,
        public price: number,
    ){}
}