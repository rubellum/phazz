import React from 'react';

import {Box, Button, ChakraProvider, Container, Heading, Link, Table, Tbody, Td, Th, Tr} from '@chakra-ui/react'
import useSWR from 'swr'
import ErrorAlert from "../components/ErrorAlert";
import Loading from "../components/Loading";

export default function (props) {
    const fetcher = (...args) => fetch(...args).then(res => res.json())
    const params = {}
    if (props.id) {
        params.id = props.id
    }
    const query = new URLSearchParams(params)
    const {data, error, isLoading} = useSWR(`${props.path.render}?${query}`, fetcher)

    if (error) return <ErrorAlert/>
    if (isLoading) return <Loading/>

    // const Submit = () => {
    //     let data = {
    //         ...type.formData,
    //         ...{token: props.token},
    //     }
    //     fetch(
    //         props.path.action, {
    //             method: 'POST',
    //             headers: {'Content-Type': 'application/json'},
    //             body: JSON.stringify(data)
    //         })
    //         .then((response) => response.json())
    //         .then((responseJson) => {
    //
    //         })
    // }

    return (
        <ChakraProvider>
            <Container my='1.5rem'>
                <Heading size='lg'><Link href='/'>Phazz</Link></Heading>
            </Container>
            <Container>
                <Container>
                    <Heading mb='3' size='md'>Job Information</Heading>
                    <Table mb='2'>
                        <Tbody>
                            <Tr>
                                <Th>Id</Th>
                                <Td>{data.item.id}</Td>
                            </Tr>
                            <Tr>
                                <Th>Name</Th>
                                <Td>{data.item.name}</Td>
                            </Tr>
                            <Tr>
                                <Th>URL</Th>
                                <Td>{data.item.url}</Td>
                            </Tr>
                            <Tr>
                                <Th>Type</Th>
                                <Td>{data.item.type}</Td>
                            </Tr>
                        </Tbody>
                    </Table>
                    <Box mt='4' textAlign={"center"}>
                        <Button as='a' colorScheme='red' href={data.item.executeLink}>
                            Execute
                        </Button>
                        <Button ml='2' as='a' href={data.item.editLink}>
                            Edit
                        </Button>
                    </Box>
                </Container>
            </Container>
        </ChakraProvider>
    )
}
