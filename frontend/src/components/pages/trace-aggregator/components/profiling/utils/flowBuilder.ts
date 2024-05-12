import {FlowItems, ProfilingItem} from "../../../../../../store/traceAggregatorProfilingStore.ts";

export class FlowBuilder {
    private readonly profilingItems: Array<ProfilingItem>

    private posX: number = 50
    private posY: number = 50

    private stepX: number = 400 // horizontal
    private stepY: number = 400 // vertical

    private flowItems: FlowItems = {
        nodes: [],
        edges: []
    }

    constructor(profilingItems: Array<ProfilingItem>) {
        this.profilingItems = profilingItems
    }

    public build(): FlowItems {
        this.posX = 0
        this.posY = 0

        this.flowItems = {
            nodes: [],
            edges: []
        }

        this.buildRecursive(this.profilingItems, null)

        return this.flowItems
    }

    private buildRecursive(items: Array<ProfilingItem>, parent: null | ProfilingItem): void {
        this.posY += this.stepY

        let isFirstItem = true

        items.map((item: ProfilingItem) => {
            if (!isFirstItem) {
                this.posX += this.stepX
            }

            isFirstItem = false

            this.flowItems.nodes.push({
                id: item.id,
                label: item.call,
                type: 'custom',
                data: item,
                position: {
                    x: this.posX,
                    y: this.posY
                },
            })

            if (parent) {
                this.flowItems.edges.push({
                    id: `${parent.id}-${item.id}`,
                    source: parent.id,
                    target: item.id,
                    style: { stroke: 'green' },
                })
            }

            if (item.link) {
                this.flowItems.edges.push({
                    id: `${item.id}-${item.link}`,
                    source: item.id,
                    target: item.link,
                    style: { stroke: 'gray' },
                })

                return
            }

            // @ts-ignore // todo: recursive oa scheme
            this.buildRecursive(item.callables, item)
        })

        this.posY -= this.stepY
    }
}
